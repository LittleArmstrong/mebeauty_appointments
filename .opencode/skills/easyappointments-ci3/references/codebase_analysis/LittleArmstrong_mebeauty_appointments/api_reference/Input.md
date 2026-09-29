# API Reference: Input.php

**Language**: PHP

**Source**: `system/core/Input.php`

---

## Classes

### CI_Input

**Inherits from**: (none)

#### Methods

##### __construct(&$security: CI_Security)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$security | CI_Security | - | - |


##### _fetch_from_array(&$array, index = NULL, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$array | None | - | - |
| index | None | NULL | - |
| xss_clean | None | FALSE | - |


##### get(index = NULL, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | NULL | - |
| xss_clean | None | FALSE | - |


##### post(index = NULL, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | NULL | - |
| xss_clean | None | FALSE | - |


##### post_get(index, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | - | - |
| xss_clean | None | FALSE | - |


##### get_post(index, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | - | - |
| xss_clean | None | FALSE | - |


##### cookie(index = NULL, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | NULL | - |
| xss_clean | None | FALSE | - |


##### server(index, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | - | - |
| xss_clean | None | FALSE | - |


##### input_stream(index = NULL, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | NULL | - |
| xss_clean | None | FALSE | - |


##### set_cookie(name, value = '', expire = 0, domain = '', path = '/', prefix = '', secure = NULL, httponly = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | None | - | - |
| value | None | '' | - |
| expire | None | 0 | - |
| domain | None | '' | - |
| path | None | '/' | - |
| prefix | None | '' | - |
| secure | None | NULL | - |
| httponly | None | NULL | - |


##### ip_address()


##### valid_ip(ip, which = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| ip | None | - | - |
| which | None | '' | - |


##### user_agent(xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| xss_clean | None | FALSE | - |


##### request_headers(xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| xss_clean | None | FALSE | - |


##### get_request_header(index, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | - | - |
| xss_clean | None | FALSE | - |


##### is_ajax_request()


##### method(upper = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| upper | None | FALSE | - |


##### __get(name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | None | - | - |




## Functions

### __construct(&$security: CI_Security)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$security | CI_Security | - | - |

**Returns**: (none)



### _fetch_from_array(&$array, index = NULL, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$array | None | - | - |
| index | None | NULL | - |
| xss_clean | None | FALSE | - |

**Returns**: (none)



### get(index = NULL, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | NULL | - |
| xss_clean | None | FALSE | - |

**Returns**: (none)



### post(index = NULL, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | NULL | - |
| xss_clean | None | FALSE | - |

**Returns**: (none)



### post_get(index, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | - | - |
| xss_clean | None | FALSE | - |

**Returns**: (none)



### get_post(index, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | - | - |
| xss_clean | None | FALSE | - |

**Returns**: (none)



### cookie(index = NULL, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | NULL | - |
| xss_clean | None | FALSE | - |

**Returns**: (none)



### server(index, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | - | - |
| xss_clean | None | FALSE | - |

**Returns**: (none)



### input_stream(index = NULL, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | NULL | - |
| xss_clean | None | FALSE | - |

**Returns**: (none)



### set_cookie(name, value = '', expire = 0, domain = '', path = '/', prefix = '', secure = NULL, httponly = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | None | - | - |
| value | None | '' | - |
| expire | None | 0 | - |
| domain | None | '' | - |
| path | None | '/' | - |
| prefix | None | '' | - |
| secure | None | NULL | - |
| httponly | None | NULL | - |

**Returns**: (none)



### ip_address()

**Returns**: (none)



### valid_ip(ip, which = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| ip | None | - | - |
| which | None | '' | - |

**Returns**: (none)



### user_agent(xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| xss_clean | None | FALSE | - |

**Returns**: (none)



### request_headers(xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| xss_clean | None | FALSE | - |

**Returns**: (none)



### get_request_header(index, xss_clean = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | - | - |
| xss_clean | None | FALSE | - |

**Returns**: (none)



### is_ajax_request()

**Returns**: (none)



### method(upper = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| upper | None | FALSE | - |

**Returns**: (none)



### __get(name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | None | - | - |

**Returns**: (none)


