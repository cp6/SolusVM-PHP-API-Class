<?php

/**
 * @about SolusVM client api wrapper
 * @author corbpie
 * @version 1.0
 */
 
class solusClientApi
{
    private string $base_url;//Base url eg "https://hostname/api/client/command.php"
    private string $key;//Api key
    private string $hash;//Api hash

    public function __construct(string $base_url, string $key, string $hash)
    {
        $this->base_url = $base_url;
        $this->key = $key;
        $this->hash = $hash;
    }

    public function doCall(string $method, array $params)
    {
        $curl = curl_init();
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($params) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            }
        } elseif ($method == 'GET') {
            if ($params) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            }
        }
        curl_setopt($curl, CURLOPT_URL, $this->base_url);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:'));
        $result = curl_exec($curl);
        preg_match('~<status>([^{]*)</status>~i', $result, $er_match);
        if (isset($er_match[1]) && $er_match[1] == 'error') {
            throw new Exception("Error with API call. Check key, hash and url are correct");
        }
        curl_close($curl);
        return $result;
    }

    public function getStatus(): bool
    {
        preg_match('~<vmstat>([^{]*)</vmstat>~i', $this->doCall('GET', array('key' => $this->key, 'hash' => $this->hash, 'action' => 'info', 'status' => 'true')), $match);
        if ($match[1] == 'online') {
            return true;
        } else {
            return false;
        }
    }

    public function ipCount(): int
    {
        preg_match('~<ipaddr>([^{]*)</ipaddr>~i', $this->doCall('GET', array('key' => $this->key, 'hash' => $this->hash, 'action' => 'info', 'ipaddr' => 'true')), $match);
        return count(explode(',', $match[1]));
    }

    public function ipMain(): string
    {
        preg_match('~<ipaddress>([^{]*)</ipaddress>~i', $this->doCall('GET', array('key' => $this->key, 'hash' => $this->hash, 'action' => 'info')), $match);
        return $match[1];
    }

    public function ip(int $number = 1): string
    {
        preg_match('~<ipaddr>([^{]*)</ipaddr>~i', $this->doCall('GET', array('key' => $this->key, 'hash' => $this->hash, 'action' => 'info', 'ipaddr' => 'true')), $match);
        $ip_arr = explode(',', $match[1]);
        return $ip_arr[($number - 1)];
    }

    public function type(): string
    {
        preg_match('~<type>([^{]*)</type>~i', $this->doCall('GET', array('key' => $this->key, 'hash' => $this->hash, 'action' => 'info')), $match);
        return $match[1];
    }

    public function hostname(): string
    {
        preg_match('~<hostname>([^{]*)</hostname>~i', $this->doCall('GET', array('key' => $this->key, 'hash' => $this->hash, 'action' => 'info')), $match);
        return $match[1];
    }

    public function node(): string
    {
        preg_match('~<node>([^{]*)</node>~i', $this->doCall('GET', array('key' => $this->key, 'hash' => $this->hash, 'action' => 'info')), $match);
        return $match[1];
    }

    public function location(): string
    {
        preg_match('~<location>([^{]*)</location>~i', $this->doCall('GET', array('key' => $this->key, 'hash' => $this->hash, 'action' => 'info', 'location' => 'true')), $match);
        return $match[1];
    }

    public function reboot(): string
    {
        preg_match('~<status>([^{]*)</status>~i', $this->doCall('POST', array('key' => $this->key, 'hash' => $this->hash, 'action' => 'reboot')), $match);
        return $match[1];
    }

    public function boot(): string
    {
        preg_match('~<status>([^{]*)</status>~i', $this->doCall('POST', array('key' => $this->key, 'hash' => $this->hash, 'action' => 'boot')), $match);
        return $match[1];
    }

    public function shutdown(): string
    {
        preg_match('~<status>([^{]*)</status>~i', $this->doCall('POST', array('key' => $this->key, 'hash' => $this->hash, 'action' => 'shutdown')), $match);
        return $match[1];
    }

    public function memMain(): string
    {
        preg_match('~<mem>([^{]*)</mem>~i', $this->doCall('GET', array('key' => $this->key, 'hash' => $this->hash, 'action' => 'info', 'mem' => 'true')), $match);
        return $match[1];
    }

    public function totalMem(string $convert_to = 'MB', int $decimals = 2)
    {
        $value = explode(',', $this->memMain());
        if ($convert_to == 'MB') {
            return number_format($value[0] / 1048576, $decimals);
        } elseif ($convert_to == 'KB') {
            return number_format($value[0] / 1024, $decimals);
        } elseif ($convert_to == 'GB') {
            return number_format($value[0] / 1073741824, $decimals);
        } else {
            return $value[0];
        }
    }

    public function memAval(string $convert_to = 'MB', int $decimals = 2)
    {
        $value = explode(',', $this->memMain());
        if ($convert_to == 'MB') {
            return number_format($value[1] / 1048576, $decimals);
        } elseif ($convert_to == 'KB') {
            return number_format($value[1] / 1024, $decimals);
        } elseif ($convert_to == 'GB') {
            return number_format($value[1] / 1073741824, $decimals);
        } else {
            return $value[0];
        }
    }

    public function memUsedPercent(int $decimals = 2)
    {
        $value = explode(',', $this->memMain());
        return number_format($value[2], $decimals);
    }

    public function memFreePercent(int $decimals = 2)
    {
        $value = explode(',', $this->memMain());
        return number_format($value[3], $decimals);
    }

    public function hddMain(): string
    {
        preg_match('~<hdd>([^{]*)</hdd>~i', $this->doCall('GET', array('key' => $this->key, 'hash' => $this->hash, 'action' => 'info', 'hdd' => 'true')), $match);
        return $match[1];
    }

    public function totalHdd(string $convert_to = 'MB', int $decimals = 2)
    {
        $value = explode(',', $this->hddMain());
        if ($convert_to == 'MB') {
            return number_format($value[0] / 1048576, $decimals);
        } elseif ($convert_to == 'KB') {
            return number_format($value[0] / 1024, $decimals);
        } elseif ($convert_to == 'GB') {
            return number_format($value[0] / 1073741824, $decimals);
        } else {
            return $value[0];
        }
    }

    public function HddAval(string $convert_to = 'MB', int $decimals = 2)
    {
        $value = explode(',', $this->hddMain());
        if ($convert_to == 'MB') {
            return number_format($value[1] / 1048576, $decimals);
        } elseif ($convert_to == 'KB') {
            return number_format($value[1] / 1024, $decimals);
        } elseif ($convert_to == 'GB') {
            return number_format($value[1] / 1073741824, $decimals);
        } else {
            return $value[0];
        }
    }

    public function hddUsedPercent(int $decimals = 2)
    {
        $value = explode(',', $this->hddMain());
        return number_format($value[2], $decimals);
    }

    public function hddFreePercent(int $decimals = 2)
    {
        $value = explode(',', $this->hddMain());
        return number_format($value[3], $decimals);
    }

    public function bwMain(): string
    {
        preg_match('~<bw>([^{]*)</bw>~i', $this->doCall('GET', array('key' => $this->key, 'hash' => $this->hash, 'action' => 'info', 'hdd' => 'true')), $match);
        return $match[1];
    }

    public function totalBw(string $convert_to = 'MB', int $decimals = 2)
    {
        $value = explode(',', $this->bwMain());
        if ($convert_to == 'MB') {
            return number_format($value[0] / 1048576, $decimals);
        } elseif ($convert_to == 'KB') {
            return number_format($value[0] / 1024, $decimals);
        } elseif ($convert_to == 'GB') {
            return number_format($value[0] / 1073741824, $decimals);
        } else {
            return $value[0];
        }
    }

    public function BwAval(string $convert_to = 'MB', int $decimals = 2)
    {
        $value = explode(',', $this->bwMain());
        if ($convert_to == 'MB') {
            return number_format($value[1] / 1048576, $decimals);
        } elseif ($convert_to == 'KB') {
            return number_format($value[1] / 1024, $decimals);
        } elseif ($convert_to == 'GB') {
            return number_format($value[1] / 1073741824, $decimals);
        } else {
            return $value[0];
        }
    }

    public function BwUsedPercent(int $decimals = 2)
    {
        $value = explode(',', $this->bwMain());
        return number_format($value[2], $decimals);
    }

    public function BwFreePercent(int $decimals = 2)
    {
        $value = explode(',', $this->bwMain());
        return number_format($value[3], $decimals);
    }

    public function allInfo(): array
    {
        $data = $this->doCall('GET', array('key' => $this->key, 'hash' => $this->hash, 'action' => 'info', 'bw' => 'true', 'hdd' => 'true', 'mem' => 'true', 'ipaddr' => 'true', 'location' => 'true', 'status' => 'true'));
        preg_match('~<vmstat>([^{]*)</vmstat>~i', $data, $status);
        preg_match('~<hostname>([^{]*)</hostname>~i', $data, $hostname);
        preg_match('~<location>([^{]*)</location>~i', $data, $location);
        preg_match('~<type>([^{]*)</type>~i', $data, $type);
        preg_match('~<node>([^{]*)</node>~i', $data, $node);
        preg_match('~<ipaddr>([^{]*)</ipaddr>~i', $data, $ip_arr);
        $ips = array();
        foreach (explode(',', $ip_arr[1]) as $an_ip) {
            $ips[] = $an_ip;
        }
        preg_match('~<bw>([^{]*)</bw>~i', $data, $bw);
        $bw_main = explode(',', $bw[1]);
        preg_match('~<mem>([^{]*)</mem>~i', $data, $mem);
        $mem_main = explode(',', $mem[1]);
        preg_match('~<hdd>([^{]*)</hdd>~i', $data, $hdd);
        $hdd_main = explode(',', $hdd[1]);
        return array(
            'status' => $status[1],
            'hostname' => $hostname[1],
            'location' => $location[1],
            'type' => $type[1],
            'node' => $node[1],
            'ip_count' => count(explode(',', $ip_arr[1])),
            'ip_list' => $ips,
            'mem_total' => number_format($mem_main[0] / 1048576, 1),
            'mem_used' => number_format(($mem_main[0] - $mem_main[1]) / 1048576, 1),
            'mem_used_percent' => intval(number_format($mem_main[2] / 1048576, 0)),
            'mem_data_type' => 'MB',
            'bw_total' => number_format($bw_main[0] / 1073741824, 1),
            'bw_used' => number_format($bw_main[1] / 1048576, 1),
            'bw_used_percent' => intval(number_format($bw_main[3] / 1073741824, 0)),
            'bw_data_type' => 'GB',
            'hdd_total' => number_format($hdd_main[0] / 1073741824, 1),
            'hdd_used' => number_format(($hdd_main[0] - $hdd_main[1]) / 1048576, 1),
            'hdd_used_percent' => intval(number_format($hdd_main[3] / 1073741824, 0)),
            'hdd_data_type' => 'GB',
            'datetime' => date('Y-m-d H:i:s')
        );
    }

    public function rdns(string $ip, string $rdns): string
    {
        return $this->doCall('POST', array('key' => $this->key, 'hash' => $this->hash, 'action' => 'rdns', 'ip' => $ip, 'rdns' => $rdns));
    }
}
