# API Reference: Accounts.php

**Language**: PHP

**Source**: `application/libraries/Accounts.php`

---

## Classes

### Accounts

**Inherits from**: (none)

#### Methods

##### __construct()


##### check_login(username: string, password: string) → ?array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |
| password | string | - | - |

**Returns**: `?array`


##### get_salt_by_username(username: string) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |

**Returns**: `string`


##### get_user_display_name(user_id: int) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |

**Returns**: `string`


##### regenerate_password(username: string, email: string) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |
| email | string | - | - |

**Returns**: `string`


##### does_account_exist(user_id: int) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |

**Returns**: `bool`


##### get_user_by_username(username: string) → ?array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |

**Returns**: `?array`


##### generate_reset_token(username: string, email: string) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |
| email | string | - | - |

**Returns**: `array`


##### validate_reset_token(token: string) → ?array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| token | string | - | - |

**Returns**: `?array`


##### reset_password_with_token(token: string, new_password: string) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| token | string | - | - |
| new_password | string | - | - |

**Returns**: `bool`




## Functions

### __construct()

**Returns**: (none)



### check_login(username: string, password: string) → ?array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |
| password | string | - | - |

**Returns**: `?array`



### get_salt_by_username(username: string) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |

**Returns**: `string`



### get_user_display_name(user_id: int) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |

**Returns**: `string`



### regenerate_password(username: string, email: string) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |
| email | string | - | - |

**Returns**: `string`



### does_account_exist(user_id: int) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |

**Returns**: `bool`



### get_user_by_username(username: string) → ?array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |

**Returns**: `?array`



### generate_reset_token(username: string, email: string) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |
| email | string | - | - |

**Returns**: `array`



### validate_reset_token(token: string) → ?array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| token | string | - | - |

**Returns**: `?array`



### reset_password_with_token(token: string, new_password: string) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| token | string | - | - |
| new_password | string | - | - |

**Returns**: `bool`


