# API Reference: Caldav_sync.php

**Language**: PHP

**Source**: `application/libraries/Caldav_sync.php`

---

## Classes

### Caldav_sync

**Inherits from**: (none)

#### Methods

##### __construct()


##### save_appointment(appointment: array, service: array, provider: array, customer: array) → ?string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| service | array | - | - |
| provider | array | - | - |
| customer | array | - | - |

**Returns**: `?string`


##### save_unavailability(unavailability: array, provider: array) → ?string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |
| provider | array | - | - |

**Returns**: `?string`


##### delete_event(provider: array, caldav_event_id: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |
| caldav_event_id | string | - | - |

**Returns**: `void`


##### get_event(provider: array, caldav_event_id: string) → ?array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |
| caldav_event_id | string | - | - |

**Returns**: `?array`


##### get_sync_events(provider: array, start_date_time: string, end_date_time: string) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |
| start_date_time | string | - | - |
| end_date_time | string | - | - |

**Returns**: `array`


##### parse_xml_events(xml: SimpleXMLElement, start_date_time: string, end_date_time: string, timezone: DateTimeZone, xml_namespace: ?string = 'd') → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| xml | SimpleXMLElement | - | - |
| start_date_time | string | - | - |
| end_date_time | string | - | - |
| timezone | DateTimeZone | - | - |
| xml_namespace | ?string | 'd' | - |

**Returns**: `array`


##### extract_ics_file_urls(body: string) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| body | string | - | - |

**Returns**: `array`


##### fetch_and_parse_ics_files(client: Client, ics_file_urls: array, start_date_time: string, end_date_time: string, timezone_OBJECT: DateTimeZone) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| client | Client | - | - |
| ics_file_urls | array | - | - |
| start_date_time | string | - | - |
| end_date_time | string | - | - |
| timezone_OBJECT | DateTimeZone | - | - |

**Returns**: `array`


##### expand_ics_content(ics_contents: string, start_date_time: string, end_date_time: string, timezone_object: DateTimeZone) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| ics_contents | string | - | - |
| start_date_time | string | - | - |
| end_date_time | string | - | - |
| timezone_object | DateTimeZone | - | - |

**Returns**: `array`


##### handle_guzzle_exception(e: GuzzleException, message: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| e | GuzzleException | - | - |
| message | string | - | - |

**Returns**: `void`


##### get_http_client(caldav_url: string, caldav_username: string, caldav_password: string) → Client

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| caldav_url | string | - | - |
| caldav_username | string | - | - |
| caldav_password | string | - | - |

**Returns**: `Client`


##### assert_safe_caldav_url(caldav_url: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| caldav_url | string | - | - |

**Returns**: `void`


##### resolve_host_ips(host: string) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| host | string | - | - |

**Returns**: `array`


##### test_connection(caldav_url: string, caldav_username: string, caldav_password: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| caldav_url | string | - | - |
| caldav_username | string | - | - |
| caldav_password | string | - | - |

**Returns**: `void`


##### get_http_client_by_provider_id(provider_id: int) → Client

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `Client`


##### get_caldav_event_uri(caldav_calendar: string, caldav_event_id: ?string = null) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| caldav_calendar | string | - | - |
| caldav_event_id | ?string | null | - |

**Returns**: `string`


##### get_appointment_ics_file(appointment: array, service: array, provider: array, customer: array) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| service | array | - | - |
| provider | array | - | - |
| customer | array | - | - |

**Returns**: `string`


##### get_unavailability_ics_file(unavailability: array, provider: array) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |
| provider | array | - | - |

**Returns**: `string`


##### parse_date_time_object(caldav_date_time: string, default_timezone_object: DateTimeZone) → DateTime

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| caldav_date_time | string | - | - |
| default_timezone_object | DateTimeZone | - | - |

**Returns**: `DateTime`


##### convert_caldav_event_to_array_event(vevent: VEvent, timezone_object: DateTimeZone) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| vevent | VEvent | - | - |
| timezone_object | DateTimeZone | - | - |

**Returns**: `array`


##### fetch_events(client: Client, start_date_time: string, end_date_time: string) → ResponseInterface

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| client | Client | - | - |
| start_date_time | string | - | - |
| end_date_time | string | - | - |

**Returns**: `ResponseInterface`




## Functions

### __construct()

**Returns**: (none)



### save_appointment(appointment: array, service: array, provider: array, customer: array) → ?string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| service | array | - | - |
| provider | array | - | - |
| customer | array | - | - |

**Returns**: `?string`



### save_unavailability(unavailability: array, provider: array) → ?string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |
| provider | array | - | - |

**Returns**: `?string`



### delete_event(provider: array, caldav_event_id: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |
| caldav_event_id | string | - | - |

**Returns**: `void`



### get_event(provider: array, caldav_event_id: string) → ?array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |
| caldav_event_id | string | - | - |

**Returns**: `?array`



### get_sync_events(provider: array, start_date_time: string, end_date_time: string) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |
| start_date_time | string | - | - |
| end_date_time | string | - | - |

**Returns**: `array`



### parse_xml_events(xml: SimpleXMLElement, start_date_time: string, end_date_time: string, timezone: DateTimeZone, xml_namespace: ?string = 'd') → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| xml | SimpleXMLElement | - | - |
| start_date_time | string | - | - |
| end_date_time | string | - | - |
| timezone | DateTimeZone | - | - |
| xml_namespace | ?string | 'd' | - |

**Returns**: `array`



### extract_ics_file_urls(body: string) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| body | string | - | - |

**Returns**: `array`



### fetch_and_parse_ics_files(client: Client, ics_file_urls: array, start_date_time: string, end_date_time: string, timezone_OBJECT: DateTimeZone) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| client | Client | - | - |
| ics_file_urls | array | - | - |
| start_date_time | string | - | - |
| end_date_time | string | - | - |
| timezone_OBJECT | DateTimeZone | - | - |

**Returns**: `array`



### expand_ics_content(ics_contents: string, start_date_time: string, end_date_time: string, timezone_object: DateTimeZone) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| ics_contents | string | - | - |
| start_date_time | string | - | - |
| end_date_time | string | - | - |
| timezone_object | DateTimeZone | - | - |

**Returns**: `array`



### handle_guzzle_exception(e: GuzzleException, message: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| e | GuzzleException | - | - |
| message | string | - | - |

**Returns**: `void`



### get_http_client(caldav_url: string, caldav_username: string, caldav_password: string) → Client

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| caldav_url | string | - | - |
| caldav_username | string | - | - |
| caldav_password | string | - | - |

**Returns**: `Client`



### assert_safe_caldav_url(caldav_url: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| caldav_url | string | - | - |

**Returns**: `void`



### resolve_host_ips(host: string) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| host | string | - | - |

**Returns**: `array`



### test_connection(caldav_url: string, caldav_username: string, caldav_password: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| caldav_url | string | - | - |
| caldav_username | string | - | - |
| caldav_password | string | - | - |

**Returns**: `void`



### get_http_client_by_provider_id(provider_id: int) → Client

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `Client`



### get_caldav_event_uri(caldav_calendar: string, caldav_event_id: ?string = null) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| caldav_calendar | string | - | - |
| caldav_event_id | ?string | null | - |

**Returns**: `string`



### get_appointment_ics_file(appointment: array, service: array, provider: array, customer: array) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| service | array | - | - |
| provider | array | - | - |
| customer | array | - | - |

**Returns**: `string`



### get_unavailability_ics_file(unavailability: array, provider: array) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |
| provider | array | - | - |

**Returns**: `string`



### parse_date_time_object(caldav_date_time: string, default_timezone_object: DateTimeZone) → DateTime

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| caldav_date_time | string | - | - |
| default_timezone_object | DateTimeZone | - | - |

**Returns**: `DateTime`



### convert_caldav_event_to_array_event(vevent: VEvent, timezone_object: DateTimeZone) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| vevent | VEvent | - | - |
| timezone_object | DateTimeZone | - | - |

**Returns**: `array`



### fetch_events(client: Client, start_date_time: string, end_date_time: string) → ResponseInterface

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| client | Client | - | - |
| start_date_time | string | - | - |
| end_date_time | string | - | - |

**Returns**: `ResponseInterface`


