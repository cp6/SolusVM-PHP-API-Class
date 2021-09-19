# SolusVM API PHP Class wrapper
A PHP wrapper class for the SolusVM API which interacts with virtual servers.

```version 1.2```

### Requires

* PHP >= 7.4 

### Usage

First edit lines 11,12,13 in ```class.php``` for your hosting providers solusVM URL along with your account API key and API hash

Call the class file on any page that uses it

```php
<?php
require_once('class.php');
```

---

Create a new instance on page of use:

```php
$call = new solusClientApi();
```

---

##### Return all server info
```php
$call->allInfo();
```
```array```

returns (edited certain values):

```json
{
  "status": "online",
  "hostname": "myservershostname.com",
  "location": "Los Angeles, CA, USA",
  "type": "kvm",
  "node": "KVM-1.LA.HOST.COM",
  "ip_count": 2,
  "ip_list": [
    "111.222.333.444",
    "111.222.333.555",
    "111.222.333.666"
  ],
  "mem_total": "512.0",
  "mem_used": "220.0",
  "mem_used_percent": 42,
  "mem_data_type": "MB",
  "bw_total": "512.0",
  "bw_used": "0.0",
  "bw_used_percent": 0,
  "bw_data_type": "GB",
  "hdd_total": "10.0",
  "hdd_used": "5.0",
  "hdd_used_percent": 50,
  "hdd_data_type": "GB",
  "datetime": "2020-04-04 15:56:29"
}
```

---
##### Get server status
```php
$call->getStatus();
```

```bool```

---
##### IP address count
```php
$call->ipCount();
```

```int```

---
##### Main ip address
```php
$call->ipMain();
```

```string```

---
##### IP address from number in array created from list
```php
$call->ip($number);
```

```string```

---
##### Server type (KVM, OVZ etc)
```php
$call->type();
```

```string```

---

##### Server hostname
```php
$call->hostname();
```

```string```

---
##### Server node
```php
$call->node();
```

```string```

---


##### Server location
```php
$call->location();
```

```string```

---

##### Reboot server
```php
$call->reboot();
```

```string```

---

##### Boot server
```php
$call->boot();
```

```string```

---

##### Shutdown server
```php
$call->shutdown();
```

```string```

---

##### Total memory for server
```php
$call->totalMem($convert_to, $decimals);
```

```string```

---

##### Memory available
```php
$call->memAval($convert_to, $decimals);
```

```string```

---

##### Memory used percent
```php
$call->memUsedPercent($decimals);
```

```string```

---

##### Memory free percent
```php
$call->memFreePercent($decimals);
```

```string```

---

##### Total HDD for server
```php
$call->totalHdd($convert_to, $decimals);
```

```string```

---

##### HDD available
```php
$call->hddAval($convert_to, $decimals);
```

```string```

---

##### HDD used percent
```php
$call->hddUsedPercent($decimals);
```

```string```

---

##### HDD free percent
```php
$call->hddFreePercent($decimals);
```

```string```

---

##### Total bandwidth for server
```php
$call->totalBw($convert_to, $decimals);
```

```string```

---

##### Bandwidth available
```php
$call->bwAval($convert_to, $decimals);
```

```string```

---

##### Bandwidth used percent
```php
$call->bwUsedPercent($decimals);
```

```string```

---
##### Bandwidth free percent
```php
$call->bwFreePercent($decimals);
```

```string```

---

##### Set up reverse DNS
```php
$call->rdns($ip, $rdns);
```

```string```
