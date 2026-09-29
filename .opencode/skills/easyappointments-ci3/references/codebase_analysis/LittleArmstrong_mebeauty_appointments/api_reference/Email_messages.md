# API Reference: Email_messages.php

**Language**: PHP

**Source**: `application/libraries/Email_messages.php`

---

## Classes

### Email_messages

**Inherits from**: (none)

#### Methods

##### __construct()


##### send_appointment_saved(appointment: array, provider: array, service: array, customer: array, settings: array, subject: string, message: string, appointment_link: string, recipient_email: string, ics_stream: string, timezone: ?string = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| provider | array | - | - |
| service | array | - | - |
| customer | array | - | - |
| settings | array | - | - |
| subject | string | - | - |
| message | string | - | - |
| appointment_link | string | - | - |
| recipient_email | string | - | - |
| ics_stream | string | - | - |
| timezone | ?string | null | - |

**Returns**: `void`


##### send_appointment_deleted(appointment: array, provider: array, service: array, customer: array, settings: array, recipient_email: string, reason: ?string = null, timezone: ?string = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| provider | array | - | - |
| service | array | - | - |
| customer | array | - | - |
| settings | array | - | - |
| recipient_email | string | - | - |
| reason | ?string | null | - |
| timezone | ?string | null | - |

**Returns**: `void`


##### send_password(password: string, recipient_email: string, settings: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| password | string | - | - |
| recipient_email | string | - | - |
| settings | array | - | - |

**Returns**: `void`


##### send_password_reset_link(reset_link: string, recipient_email: string, settings: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| reset_link | string | - | - |
| recipient_email | string | - | - |
| settings | array | - | - |

**Returns**: `void`


##### get_php_mailer(recipient_email: ?string = null, subject: ?string = null, html: ?string = null) → PHPMailer

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| recipient_email | ?string | null | - |
| subject | ?string | null | - |
| html | ?string | null | - |

**Returns**: `PHPMailer`




## Functions

### __construct()

**Returns**: (none)



### send_appointment_saved(appointment: array, provider: array, service: array, customer: array, settings: array, subject: string, message: string, appointment_link: string, recipient_email: string, ics_stream: string, timezone: ?string = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| provider | array | - | - |
| service | array | - | - |
| customer | array | - | - |
| settings | array | - | - |
| subject | string | - | - |
| message | string | - | - |
| appointment_link | string | - | - |
| recipient_email | string | - | - |
| ics_stream | string | - | - |
| timezone | ?string | null | - |

**Returns**: `void`



### send_appointment_deleted(appointment: array, provider: array, service: array, customer: array, settings: array, recipient_email: string, reason: ?string = null, timezone: ?string = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| provider | array | - | - |
| service | array | - | - |
| customer | array | - | - |
| settings | array | - | - |
| recipient_email | string | - | - |
| reason | ?string | null | - |
| timezone | ?string | null | - |

**Returns**: `void`



### send_password(password: string, recipient_email: string, settings: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| password | string | - | - |
| recipient_email | string | - | - |
| settings | array | - | - |

**Returns**: `void`



### send_password_reset_link(reset_link: string, recipient_email: string, settings: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| reset_link | string | - | - |
| recipient_email | string | - | - |
| settings | array | - | - |

**Returns**: `void`



### get_php_mailer(recipient_email: ?string = null, subject: ?string = null, html: ?string = null) → PHPMailer

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| recipient_email | ?string | null | - |
| subject | ?string | null | - |
| html | ?string | null | - |

**Returns**: `PHPMailer`


