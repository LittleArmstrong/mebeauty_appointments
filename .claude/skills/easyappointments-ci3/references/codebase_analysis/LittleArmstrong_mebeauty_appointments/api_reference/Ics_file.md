# API Reference: Ics_file.php

**Language**: PHP

**Source**: `application/libraries/Ics_file.php`

---

## Classes

### Ics_file

**Inherits from**: (none)

#### Methods

##### __construct()


##### get_stream(appointment: array, service: array, provider: array, customer: array) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| service | array | - | - |
| provider | array | - | - |
| customer | array | - | - |

**Returns**: `string`


##### get_unavailability_stream(unavailability: array, provider: array) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |
| provider | array | - | - |

**Returns**: `string`


##### generate_uid(db_record_id: int) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| db_record_id | int | - | - |

**Returns**: `string`


##### generate_sequence(update_datetime: ?string) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| update_datetime | ?string | - | - |

**Returns**: `int`




## Functions

### __construct()

**Returns**: (none)



### get_stream(appointment: array, service: array, provider: array, customer: array) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| service | array | - | - |
| provider | array | - | - |
| customer | array | - | - |

**Returns**: `string`



### get_unavailability_stream(unavailability: array, provider: array) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |
| provider | array | - | - |

**Returns**: `string`



### generate_uid(db_record_id: int) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| db_record_id | int | - | - |

**Returns**: `string`



### generate_sequence(update_datetime: ?string) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| update_datetime | ?string | - | - |

**Returns**: `int`


