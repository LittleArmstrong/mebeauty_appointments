# API Reference: Cache_redis.php

**Language**: PHP

**Source**: `system/libraries/Cache/drivers/Cache_redis.php`

---

## Classes

### CI_Cache_redis

**Inherits from**: CI_Driver

#### Methods

##### __construct()


##### get(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |


##### save(id, data, ttl = 60, raw = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |
| data | None | - | - |
| ttl | None | 60 | - |
| raw | None | FALSE | - |


##### delete(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |


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


##### get_metadata(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |


##### is_supported()


##### __destruct()




## Functions

### __construct()

**Returns**: (none)



### get(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |

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



### delete(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |

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



### get_metadata(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |

**Returns**: (none)



### is_supported()

**Returns**: (none)



### __destruct()

**Returns**: (none)


