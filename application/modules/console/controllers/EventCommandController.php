<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EventCommandController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->input->is_cli_request()) {
            exit('CLI only');
        }

        $this->load->model([
			'events'
		]);

		$this->load->library('curly');
    }

    /**
     * php index.php EventCommandController/sync 2025
     */
    public function sync($year = null)
    {
        $year = $year ?? date('Y');

        echo "Sync calendar {$year}\n";

        $items = $this->_fetchHoliday($year);
        if (!$items) {
            echo "No data\n";
            return;
        }

        $events = $this->_groupByDateRange($items);

        foreach ($events as $event) {
			$model = new Events;

			$event['category'] = $this->mapCategory($event['raw_type']);
			unset($event['raw_type']);

            $model->setAttributes($event);
			$model->save();
        }

        echo "Inserted " . count($events) . " events\n";
    }

    private function _fetchHoliday($year)
    {
        $url = "https://use.api.co.id/holidays/indonesia?year={$year}";

        $response = $this->curly
			->setTimeout(10)
			->setHeaders([
				"x-api-co-id: " . getenv('APICOID_KEY'),
				"Accept: application/json"
			])
			->get($url);

		if (!$response['success']) {
			echo "Error fetching data: HTTP " . $response['status'] . "\n";
			return [];
		}

		$data = $response['body'];
		if (empty($data['data'])) {
			return [];
		}

		$items = [];
		foreach ($data['data'] as $item) {
			$items[] = [
				'name' => $item['name'],
				'date' => $item['date'],
				'type' => $item['type'],
				"is_holiday" => $item["is_holiday"],
				"is_joint_holiday" => $item["is_joint_holiday"],
				"is_observance" => $item["is_observance"]
			];
		}

		return $items;
    }

    /**
     * Gabung event berdasarkan tanggal berurutan
     */
    private function _groupByDateRange(array $items)
	{
		usort($items, fn ($a, $b) => strcmp($a['date'], $b['date']));

		$results = [];
		$current = null;

		foreach ($items as $item) {
			$date = $item['date'];

			if ($current === null) {
				$current = $this->_initEvent($item);
				continue;
			}

			$prevDate = date('Y-m-d', strtotime($current['end_date']));
			$nextDate = date('Y-m-d', strtotime($prevDate . ' +1 day'));

			// 👉 SYARAT GABUNG HARUS KETAT
			if (
				$nextDate === $date &&
				$current['raw_type'] === $item['type']
			) {
				$current['end_date'] = $date . ' 23:59:59';
				$current['all_day']  = 0;
				$current['description'] .= ' | ' . $item['name'];
			} else {
				$results[] = $this->_finalize($current);
				$current = $this->_initEvent($item);
			}
		}

		if ($current) {
			$results[] = $this->_finalize($current);
		}

		return $results;
	}

    private function _initEvent(array $item)
    {
        return [
            'title'       => $item['name'],
            'description' => $item['type'] . ' | ' . $item['name'],
            'start_date'  => $item['date'] . ' 00:00:00',
            'end_date'    => $item['date'] . ' 23:59:59',
            'all_day'     => 1,
            'raw_type'    => $item['type'], // buat model mapping kategori
        ];
    }

    private function _finalize(array $event)
    {
        if (
            date('Y-m-d', strtotime($event['start_date'])) !==
            date('Y-m-d', strtotime($event['end_date']))
        ) {
            $event['all_day'] = 0;
        }

        return $event;
    }

	private function mapCategory($type)
    {
        return match ($type) {
            'Public Holiday', 'National Holiday' => 'holiday',
            'Joint Holiday'                      => 'joint-leave',
            'Observance'                        => 'observance',
            default                             => 'others',
        };
    }
}
