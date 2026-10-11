# API Reference: Service_categories_model.php

**Language**: PHP

**Source**: `application/models/Service_categories_model.php`

---

## Classes

### Service_categories_model

**Inherits from**: EA_Model

#### Methods

##### save(service_category: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_category | array | - | - |

**Returns**: `int`


##### validate(service_category: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_category | array | - | - |

**Returns**: `void`


##### insert(service_category: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_category | array | - | - |

**Returns**: `int`


##### update(service_category: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_category | array | - | - |

**Returns**: `int`


##### delete(service_category_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_category_id | int | - | - |

**Returns**: `void`


##### find(service_category_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_category_id | int | - | - |

**Returns**: `array`


##### value(service_category_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_category_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`


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


##### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`


##### load(&$service_category: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$service_category | array | - | - |
| resources | array | - | - |


##### api_encode(&$service_category: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$service_category | array | - | - |

**Returns**: `void`


##### api_decode(&$service_category: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$service_category | array | - | - |
| base | ?array | null | - |

**Returns**: `void`




## Functions

### save(service_category: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_category | array | - | - |

**Returns**: `int`



### validate(service_category: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_category | array | - | - |

**Returns**: `void`



### insert(service_category: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_category | array | - | - |

**Returns**: `int`



### update(service_category: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_category | array | - | - |

**Returns**: `int`



### delete(service_category_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_category_id | int | - | - |

**Returns**: `void`



### find(service_category_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_category_id | int | - | - |

**Returns**: `array`



### value(service_category_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| service_category_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`



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



### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`



### load(&$service_category: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$service_category | array | - | - |
| resources | array | - | - |

**Returns**: (none)



### api_encode(&$service_category: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$service_category | array | - | - |

**Returns**: `void`



### api_decode(&$service_category: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$service_category | array | - | - |
| base | ?array | null | - |

**Returns**: `void`


