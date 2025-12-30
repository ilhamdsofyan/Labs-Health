<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Curly
{
    protected int $timeout = 30;
    protected array $headers = [];

    public function setTimeout(int $seconds): self
    {
        $this->timeout = $seconds;
        return $this;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    public function addHeader(string $key, string $value): self
    {
        $this->headers[] = "{$key}: {$value}";
        return $this;
    }

    public function get(string $url, array $query = []): array
    {
        if ($query) {
            $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($query);
        }

        return $this->request('GET', $url);
    }

    public function post(string $url, array|string $payload = []): array
    {
        return $this->request('POST', $url, $payload);
    }

    public function put(string $url, array|string $payload = []): array
    {
        return $this->request('PUT', $url, $payload);
    }

    public function delete(string $url): array
    {
        return $this->request('DELETE', $url);
    }

    protected function request(string $method, string $url, array|string|null $payload = null): array
    {
        $ch = curl_init($url);

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_FAILONERROR    => false,
        ];

        if ($this->headers) {
            $options[CURLOPT_HTTPHEADER] = $this->headers;
        }

        if ($payload !== null) {
            if (is_array($payload)) {
                $payload = json_encode($payload);
                $this->headers[] = 'Content-Type: application/json';
            }

            $options[CURLOPT_POSTFIELDS] = $payload;
        }

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $errno    = curl_errno($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return [
            'success' => $errno === 0 && $status < 400,
            'status'  => $status,
            'error'   => $error ?: null,
            'body'    => $this->decode($response),
            'raw'     => $response,
        ];
    }

    protected function decode(string|false $response)
    {
        if ($response === false) {
            return null;
        }

        $json = json_decode($response, true);
        return json_last_error() === JSON_ERROR_NONE ? $json : $response;
    }
}
