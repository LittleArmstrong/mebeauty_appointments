# API Reference: Webhooks_model.php

**Language**: PHP

**Source**: `application/models/Webhooks_model.php`

---

## Classes

### Webhooks_model

**Inherits from**: EA_Model

#### Methods

##### save(webhook: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| webhook | array | - | - |

**Returns**: `int`


##### validate(webhook: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| webhook | array | - | - |

**Returns**: `void`


##### insert(webhook: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| webhook | array | - | - |

**Returns**: `int`


##### update(webhook: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| webhook | array | - | - |

**Returns**: `int`


##### delete(webhook_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| webhook_id | int | - | - |

**Returns**: `void`


##### find(webhook_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| webhook_id | int | - | - |

**Returns**: `array`


##### value(webhook_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| webhook_id | int | - | - |
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


##### load(&$webhook: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$webhook | array | - | - |
| resources | array | - | - |


##### api_encode(&$webhook: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$webhook | array | - | - |

**Returns**: `void`


##### api_decode(&$webhook: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$webhook | array | - | - |
| base | ?array | null | - |

**Returns**: `void`




## Functions

### save(webhook: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| webhook | array | - | - |

**Returns**: `int`



### validate(webhook: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| webhook | array | - | - |

**Returns**: `void`



### insert(webhook: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| webhook | array | - | - |

**Returns**: `int`



### update(webhook: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| webhook | array | - | - |

**Returns**: `int`



### delete(webhook_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| webhook_id | int | - | - |

**Returns**: `void`



### find(webhook_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| webhook_id | int | - | - |

**Returns**: `array`



### value(webhook_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| webhook_id | int | - | - |
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



### load(&$webhook: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$webhook | array | - | - |
| resources | array | - | - |

**Returns**: (none)



### api_encode(&$webhook: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$webhook | array | - | - |

**Returns**: `void`



### api_decode(&$webhook: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$webhook | array | - | - |
| base | ?array | null | - |

**Returns**: `void`


