# API Reference: password_helper.php

**Language**: PHP

**Source**: `application/helpers/password_helper.php`

---

## Functions

### hash_password(salt: string, password: string) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| salt | string | - | - |
| password | string | - | - |

**Returns**: `string`



### verify_password(salt: string, password: string, hash: string) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| salt | string | - | - |
| password | string | - | - |
| hash | string | - | - |

**Returns**: `bool`



### password_needs_rehash_check(hash: string) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| hash | string | - | - |

**Returns**: `bool`



### generate_salt() → string

**Returns**: `string`


