<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @link https://stackoverflow.com/a/30083294
 */
class MY_Exceptions extends CI_Exceptions
{
	private $stati = [
		100	=> 'Continue',
		101	=> 'Switching Protocols',

		200	=> 'OK',
		201	=> 'Created',
		202	=> 'Accepted',
		203	=> 'Non-Authoritative Information',
		204	=> 'No Content',
		205	=> 'Reset Content',
		206	=> 'Partial Content',

		300	=> 'Multiple Choices',
		301	=> 'Moved Permanently',
		302	=> 'Found',
		303	=> 'See Other',
		304	=> 'Not Modified',
		305	=> 'Use Proxy',
		307	=> 'Temporary Redirect',

		400	=> 'Bad Request',
		401	=> 'Unauthorized',
		402	=> 'Payment Required',
		403	=> 'Forbidden',
		404	=> 'Not Found',
		405	=> 'Method Not Allowed',
		406	=> 'Not Acceptable',
		407	=> 'Proxy Authentication Required',
		408	=> 'Request Timeout',
		409	=> 'Conflict',
		410	=> 'Gone',
		411	=> 'Length Required',
		412	=> 'Precondition Failed',
		413	=> 'Request Entity Too Large',
		414	=> 'Request-URI Too Long',
		415	=> 'Unsupported Media Type',
		416	=> 'Requested Range Not Satisfiable',
		417	=> 'Expectation Failed',
		422	=> 'Unprocessable Entity',
		426	=> 'Upgrade Required',
		428	=> 'Precondition Required',
		429	=> 'Too Many Requests',
		431	=> 'Request Header Fields Too Large',

		500	=> 'Internal Server Error',
		501	=> 'Not Implemented',
		502	=> 'Bad Gateway',
		503	=> 'Service Unavailable',
		504	=> 'Gateway Timeout',
		505	=> 'HTTP Version Not Supported',
		511	=> 'Network Authentication Required',
	];

	protected $handling = false;

	public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
	{
		// prevent recursive errors causing infinite loops
		if ($this->handling) {
			return parent::show_error($heading, $message, $template, $status_code);
		}
		$this->handling = true;

		if (!class_exists('CI_Controller')) {
			$this->handling = false;
			return parent::show_error($heading, $message, 'error_general', 500);
		}

		if ($template == 'error_general') {
			$template = 'custom_error_general';
		}

		$CI =& get_instance();
		$templates_path = config_item('error_views_path');

		if (empty($templates_path)) {
			$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;
		}

		if (is_cli()) {
			$message = "\t".(is_array($message) ? implode("\n\t", $message) : $message);
			$template = 'cli'.DIRECTORY_SEPARATOR.$template;

            // For CLI just render the CLI view and return
            $view = $CI->load->view('errors/'.$template, [
                'heading' => $heading,
                'message' => $message
            ], true);

        } else {
            set_status_header($status_code);
            // keep message as plain text (views are responsible to wrap in <p> or markup)
            $message = (is_array($message) ? implode("\n", $message) : $message);
            $template = 'html'.'/'.$template;

			if (!isset($CI->layout)) {
				$CI->load->library('Layout');
			}

            $layoutData = [
                'data' => [
                    'heading' => def($this->stati, $status_code, $heading),
                    'message' => $message,
                    'status_code' => $status_code,
                    'layout' => $CI->layout ?? null,
                ],
                'title' => $status_code .' '. def($this->stati, $status_code),
                'view' => 'errors/'.$template
            ];

            try {
                $CI->layout->setLayout('error');
                $CI->layout->viewFolder = 'errors/html';
                $view = $CI->layout->render('custom_error_general', $layoutData['data'], true);

                // fallback to error_simple layout when layout returns empty
                if (empty($view)) {
                    try {
                        $CI->layout->setLayout('error_simple');
                        $view = $CI->layout->render('custom_error_general', $layoutData['data'], true);
                    } catch (Exception $_e) {
                        // ignore and fallback to plain view
                    }
                }

                // fallback to plain view when layout returns empty or fails silently
                if (empty($view)) {
                    $view = $CI->load->view('errors/'.$template, $layoutData['data'], true);
                }

            } catch (Exception $e) {
                // safe fallback to simple error view
                try {
                    if (!empty($CI->layout)) {
                        $CI->layout->setLayout('error_simple');
                        $CI->layout->viewFolder = 'errors/html';
                        $view = $CI->layout->render('custom_error_general', $layoutData['data'], true);
                    }
                } catch (Exception $_e) {
                    // ignore
                }

                if (empty($view)) {
                    $view = $CI->load->view('errors/'.$template, $layoutData['data'], true);
                }
            }
        }

		$this->handling = false;
		ob_start();
		echo $view;
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	public function show_exception($exception)
	{
		// prevent recursive handling
		if ($this->handling) {
			return parent::show_exception($exception);
		}
		$this->handling = true;

		if (!class_exists('CI_Controller')) {
			$this->handling = false;
			return parent::show_exception($exception);
		}

		$CI =& get_instance();
		$templates_path = config_item('error_views_path');

		if (empty($templates_path)) {
			$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;
		}

		$message = $exception->getMessage();
		if (empty($message)) {
			$message = '(null)';
		}

		set_status_header(500);

		$layoutData = [
			'data' => [
				'heading' => 'Exception',
				'message' => $message,
				'exception' => $exception,
				'status_code' => 500,
				'layout' => $CI->layout ?? null,
			],
			'title' => 'Exception',
			'view' => 'errors/html/error_exception'
		];

		try {
			if (!isset($CI->layout)) {
				$CI->load->library('Layout');
			}

			$CI->layout->setLayout('error');
			$CI->layout->viewFolder = 'errors/html';
			$view = $CI->layout->render('error_exception', $layoutData['data'], true);

			if (empty($view)) {
				try {
					$CI->layout->setLayout('error_simple');
					$view = $CI->layout->render('error_exception', $layoutData['data'], true);
				} catch (Exception $_e) {
					// ignore
				}
			}

			if (empty($view)) {
				$view = $CI->load->view('errors/html/error_exception', $layoutData['data'], true);
			}

		} catch (Exception $e) {
			try {
				if (!empty($CI->layout)) {
					$CI->layout->setLayout('error_simple');
					$CI->layout->viewFolder = 'errors/html';
					$view = $CI->layout->render('error_exception', $layoutData['data'], true);
				}
			} catch (Exception $_e) {
				// ignore
			}

			if (empty($view)) {
				$view = $CI->load->view('errors/html/error_exception', $layoutData['data'], true);
			}
		}

		$this->handling = false;
		echo $view;
	}

	public function show_php_error($severity, $message, $filepath, $line)
	{
		// prevent recursive handling
		if ($this->handling) {
			return parent::show_php_error($severity, $message, $filepath, $line);
		}
		$this->handling = true;

		if (!class_exists('CI_Controller')) {
			$this->handling = false;
			return parent::show_php_error($severity, $message, $filepath, $line);
		}

		$CI =& get_instance();
		$templates_path = config_item('error_views_path');

		if (empty($templates_path)) {
			$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;
		}

		$severityName = isset($this->stati[$severity]) ? $this->stati[$severity] : $severity;

		// shorten filepath for non-cli
		if (!is_cli()) {
			$filepath = str_replace('\\', '/', $filepath);
			if (FALSE !== strpos($filepath, '/')) {
				$x = explode('/', $filepath);
				$filepath = $x[count($x)-2].'/'.end($x);
			}
		}

		set_status_header(500);

		$layoutData = [
			'data' => [
				'severity' => $severityName,
				'message' => $message,
				'filepath' => $filepath,
				'line' => $line,
				'status_code' => 500,
				'layout' => $CI->layout ?? null,
			],
			'title' => 'PHP Error',
			'view' => 'errors/html/error_php'
		];

		try {
			if (!isset($CI->layout)) {
				$CI->load->library('Layout');
			}

			$CI->layout->setLayout('error');
			$CI->layout->viewFolder = 'errors/html';
			$view = $CI->layout->render('error_php', $layoutData['data'], true);

			if (empty($view)) {
				try {
					$CI->layout->setLayout('error_simple');
					$view = $CI->layout->render('error_php', $layoutData['data'], true);
				} catch (Exception $_e) {
					// ignore
				}
			}

			if (empty($view)) {
				$view = $CI->load->view('errors/html/error_php', $layoutData['data'], true);
			}

		} catch (Exception $e) {
			try {
				if (!empty($CI->layout)) {
					$CI->layout->setLayout('error_simple');
					$CI->layout->viewFolder = 'errors/html';
					$view = $CI->layout->render('error_php', $layoutData['data'], true);
				}
			} catch (Exception $_e) {
				// ignore
			}

			if (empty($view)) {
				$view = $CI->load->view('errors/html/error_php', $layoutData['data'], true);
			}
		}

		$this->handling = false;
		echo $view;
}
}
