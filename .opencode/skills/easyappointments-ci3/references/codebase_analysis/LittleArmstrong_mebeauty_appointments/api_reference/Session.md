# API Reference: Session.php

**Language**: PHP

**Source**: `system/libraries/Session/Session.php`

---

## Classes

### CI_Session

**Inherits from**: (none)

#### Methods

##### __construct(params: array = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | array | array( | - |


##### _ci_load_classes(driver)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| driver | None | - | - |


##### _configure(&$params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$params | None | - | - |


##### _configure_sid_length()


##### _ci_init_vars()


##### mark_as_flash(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |


##### get_flash_keys()


##### unmark_flash(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |


##### mark_as_temp(key, ttl = 300)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| ttl | None | 300 | - |


##### get_temp_keys()


##### unmark_temp(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |


##### __get(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |


##### __isset(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |


##### __set(key, value)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| value | None | - | - |


##### sess_destroy()


##### sess_regenerate(destroy = null)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| destroy | None | null | - |


##### userdata(key = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | NULL | - |


##### set_userdata(data, value = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| value | None | NULL | - |


##### unset_userdata(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |


##### all_userdata()


##### has_userdata(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |


##### flashdata(key = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | NULL | - |


##### set_flashdata(data, value = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| value | None | NULL | - |


##### keep_flashdata(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |


##### tempdata(key = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | NULL | - |


##### set_tempdata(data, value = NULL, ttl = 300)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| value | None | NULL | - |
| ttl | None | 300 | - |


##### unset_tempdata(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |




## Functions

### __construct(params: array = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | array | array( | - |

**Returns**: (none)



### _ci_load_classes(driver)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| driver | None | - | - |

**Returns**: (none)



### _configure(&$params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$params | None | - | - |

**Returns**: (none)



### _configure_sid_length()

**Returns**: (none)



### _ci_init_vars()

**Returns**: (none)



### mark_as_flash(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |

**Returns**: (none)



### get_flash_keys()

**Returns**: (none)



### unmark_flash(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |

**Returns**: (none)



### mark_as_temp(key, ttl = 300)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| ttl | None | 300 | - |

**Returns**: (none)



### get_temp_keys()

**Returns**: (none)



### unmark_temp(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |

**Returns**: (none)



### __get(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |

**Returns**: (none)



### __isset(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |

**Returns**: (none)



### __set(key, value)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| value | None | - | - |

**Returns**: (none)



### sess_destroy()

**Returns**: (none)



### sess_regenerate(destroy = null)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| destroy | None | null | - |

**Returns**: (none)



### userdata(key = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | NULL | - |

**Returns**: (none)



### set_userdata(data, value = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| value | None | NULL | - |

**Returns**: (none)



### unset_userdata(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |

**Returns**: (none)



### all_userdata()

**Returns**: (none)



### has_userdata(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |

**Returns**: (none)



### flashdata(key = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | NULL | - |

**Returns**: (none)



### set_flashdata(data, value = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| value | None | NULL | - |

**Returns**: (none)



### keep_flashdata(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |

**Returns**: (none)



### tempdata(key = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | NULL | - |

**Returns**: (none)



### set_tempdata(data, value = NULL, ttl = 300)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| value | None | NULL | - |
| ttl | None | 300 | - |

**Returns**: (none)



### unset_tempdata(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |

**Returns**: (none)


