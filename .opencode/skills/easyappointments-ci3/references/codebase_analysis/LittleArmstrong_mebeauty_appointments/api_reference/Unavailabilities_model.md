# API Reference: Unavailabilities_model.php

**Language**: PHP

**Source**: `application/models/Unavailabilities_model.php`

---

## Classes

### Unavailabilities_model

**Inherits from**: EA_Model

#### Methods

##### save(unavailability: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |

**Returns**: `int`


##### validate(unavailability: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |

**Returns**: `void`


##### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`


##### insert(unavailability: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |

**Returns**: `int`


##### update(unavailability: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |

**Returns**: `int`


##### delete(unavailability_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability_id | int | - | - |

**Returns**: `void`


##### find(unavailability_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability_id | int | - | - |

**Returns**: `array`


##### value(unavailability_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability_id | int | - | - |
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


##### load(&$unavailability: array, resources: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$unavailability | array | - | - |
| resources | array | - | - |

**Returns**: `void`


##### api_encode(&$unavailability: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$unavailability | array | - | - |

**Returns**: `void`


##### api_decode(&$unavailability: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$unavailability | array | - | - |
| base | ?array | null | - |

**Returns**: `void`




## Functions

### save(unavailability: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |

**Returns**: `int`



### validate(unavailability: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |

**Returns**: `void`



### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`



### insert(unavailability: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |

**Returns**: `int`



### update(unavailability: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |

**Returns**: `int`



### delete(unavailability_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability_id | int | - | - |

**Returns**: `void`



### find(unavailability_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability_id | int | - | - |

**Returns**: `array`



### value(unavailability_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability_id | int | - | - |
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



### load(&$unavailability: array, resources: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$unavailability | array | - | - |
| resources | array | - | - |

**Returns**: `void`



### api_encode(&$unavailability: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$unavailability | array | - | - |

**Returns**: `void`



### api_decode(&$unavailability: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$unavailability | array | - | - |
| base | ?array | null | - |

**Returns**: `void`


