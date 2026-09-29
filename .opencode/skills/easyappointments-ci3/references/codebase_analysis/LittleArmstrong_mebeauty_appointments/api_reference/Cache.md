# API Reference: Cache.php

**Language**: PHP

**Source**: `system/libraries/Cache/Cache.php`

---

## Classes

### CI_Cache

**Inherits from**: CI_Driver_Library

#### Methods

##### __construct(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |


##### get(id)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |


##### save(id, data, ttl = 60, raw = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |
| data | None | - | - |
| ttl | None | 60 | - |
| raw | None | FALSE | - |


##### delete(id)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |


##### increment(id, offset = 1)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |
| offset | None | 1 | - |


##### decrement(id, offset = 1)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |
| offset | None | 1 | - |


##### clean()


##### cache_info(type = 'user')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'user' | - |


##### get_metadata(id)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |


##### is_supported(driver)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| driver | None | - | - |


##### get_loaded_driver()




## Functions

### __construct(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |

**Returns**: (none)



### get(id)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |

**Returns**: (none)



### save(id, data, ttl = 60, raw = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |
| data | None | - | - |
| ttl | None | 60 | - |
| raw | None | FALSE | - |

**Returns**: (none)



### delete(id)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |

**Returns**: (none)



### increment(id, offset = 1)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |
| offset | None | 1 | - |

**Returns**: (none)



### decrement(id, offset = 1)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |
| offset | None | 1 | - |

**Returns**: (none)



### clean()

**Returns**: (none)



### cache_info(type = 'user')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'user' | - |

**Returns**: (none)



### get_metadata(id)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |

**Returns**: (none)



### is_supported(driver)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| driver | None | - | - |

**Returns**: (none)



### get_loaded_driver()

**Returns**: (none)


