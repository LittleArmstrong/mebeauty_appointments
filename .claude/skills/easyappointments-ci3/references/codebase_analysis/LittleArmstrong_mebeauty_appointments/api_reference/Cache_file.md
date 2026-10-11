# API Reference: Cache_file.php

**Language**: PHP

**Source**: `system/libraries/Cache/drivers/Cache_file.php`

---

## Classes

### CI_Cache_file

**Inherits from**: CI_Driver

#### Methods

##### __construct()


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


##### cache_info(type = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | NULL | - |


##### get_metadata(id)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |


##### is_supported()


##### _get(id)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |




## Functions

### __construct()

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



### cache_info(type = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | NULL | - |

**Returns**: (none)



### get_metadata(id)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |

**Returns**: (none)



### is_supported()

**Returns**: (none)



### _get(id)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |

**Returns**: (none)


