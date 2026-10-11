# API Reference: Consents_model.php

**Language**: PHP

**Source**: `application/models/Consents_model.php`

---

## Classes

### Consents_model

**Inherits from**: EA_Model

#### Methods

##### save(consent: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| consent | array | - | - |

**Returns**: `int`


##### validate(consent: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| consent | array | - | - |

**Returns**: `void`


##### insert(consent: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| consent | array | - | - |

**Returns**: `int`


##### update(consent: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| consent | array | - | - |

**Returns**: `int`


##### delete(consent_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| consent_id | int | - | - |

**Returns**: `void`


##### find(consent_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| consent_id | int | - | - |

**Returns**: `array`


##### value(consent_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| consent_id | int | - | - |
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


##### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
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


##### load(&$consent: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$consent | array | - | - |
| resources | array | - | - |




## Functions

### save(consent: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| consent | array | - | - |

**Returns**: `int`



### validate(consent: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| consent | array | - | - |

**Returns**: `void`



### insert(consent: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| consent | array | - | - |

**Returns**: `int`



### update(consent: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| consent | array | - | - |

**Returns**: `int`



### delete(consent_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| consent_id | int | - | - |

**Returns**: `void`



### find(consent_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| consent_id | int | - | - |

**Returns**: `array`



### value(consent_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| consent_id | int | - | - |
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



### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
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



### load(&$consent: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$consent | array | - | - |
| resources | array | - | - |

**Returns**: (none)


