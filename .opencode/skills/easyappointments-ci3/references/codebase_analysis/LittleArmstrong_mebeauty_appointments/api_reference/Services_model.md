# API Reference: Services_model.php

**Language**: PHP

**Source**: `application/models/Services_model.php`

---

## Classes

### Services_model

**Inherits from**: EA_Model

#### Methods

##### save(service: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service | array | - | - |

**Returns**: `int`


##### validate(service: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service | array | - | - |

**Returns**: `void`


##### insert(service: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service | array | - | - |

**Returns**: `int`


##### update(service: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service | array | - | - |

**Returns**: `int`


##### get_provider_ids(service_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_id | int | - | - |

**Returns**: `array`


##### set_provider_ids(service_id: int, provider_ids: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_id | int | - | - |
| provider_ids | array | - | - |

**Returns**: `void`


##### delete(service_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_id | int | - | - |

**Returns**: `void`


##### find(service_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_id | int | - | - |

**Returns**: `array`


##### value(service_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`


##### get_available_services(without_private: bool = false) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| without_private | bool | false | - |

**Returns**: `array`


##### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`


##### query() → CI_DB_query_builder

**Returns**: `CI_DB_query_builder`


##### search(keyword: string, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| keyword | string | - | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`


##### to_options(where: array|string|null = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |

**Returns**: `array`


##### load(&$service: array, resources: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$service | array | - | - |
| resources | array | - | - |

**Returns**: `void`


##### api_encode(&$service: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$service | array | - | - |

**Returns**: `void`


##### api_decode(&$service: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$service | array | - | - |
| base | ?array | null | - |

**Returns**: `void`




## Functions

### save(service: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service | array | - | - |

**Returns**: `int`



### validate(service: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service | array | - | - |

**Returns**: `void`



### insert(service: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service | array | - | - |

**Returns**: `int`



### update(service: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service | array | - | - |

**Returns**: `int`



### get_provider_ids(service_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_id | int | - | - |

**Returns**: `array`



### set_provider_ids(service_id: int, provider_ids: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_id | int | - | - |
| provider_ids | array | - | - |

**Returns**: `void`



### delete(service_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_id | int | - | - |

**Returns**: `void`



### find(service_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_id | int | - | - |

**Returns**: `array`



### value(service_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`



### get_available_services(without_private: bool = false) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| without_private | bool | false | - |

**Returns**: `array`



### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`



### query() → CI_DB_query_builder

**Returns**: `CI_DB_query_builder`



### search(keyword: string, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| keyword | string | - | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`



### to_options(where: array|string|null = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |

**Returns**: `array`



### load(&$service: array, resources: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$service | array | - | - |
| resources | array | - | - |

**Returns**: `void`



### api_encode(&$service: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$service | array | - | - |

**Returns**: `void`



### api_decode(&$service: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$service | array | - | - |
| base | ?array | null | - |

**Returns**: `void`


