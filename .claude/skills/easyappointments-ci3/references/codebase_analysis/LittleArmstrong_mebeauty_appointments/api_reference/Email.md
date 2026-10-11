# API Reference: Email.php

**Language**: PHP

**Source**: `system/libraries/Email.php`

---

## Classes

### CI_Email

**Inherits from**: (none)

#### Methods

##### __construct(config: array = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | array | array( | - |


##### initialize(config: array = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | array | array( | - |


##### clear(clear_attachments = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| clear_attachments | None | FALSE | - |


##### from(from, name = '', return_path = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| from | None | - | - |
| name | None | '' | - |
| return_path | None | NULL | - |


##### reply_to(replyto, name = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| replyto | None | - | - |
| name | None | '' | - |


##### to(to)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| to | None | - | - |


##### cc(cc)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| cc | None | - | - |


##### bcc(bcc, limit = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| bcc | None | - | - |
| limit | None | '' | - |


##### subject(subject)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| subject | None | - | - |


##### message(body)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| body | None | - | - |


##### attach(file, disposition = '', newname = NULL, mime = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| file | None | - | - |
| disposition | None | '' | - |
| newname | None | NULL | - |
| mime | None | '' | - |


##### attachment_cid(filename)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filename | None | - | - |


##### set_header(header, value)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| header | None | - | - |
| value | None | - | - |


##### _str_to_array(email)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| email | None | - | - |


##### set_alt_message(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### set_mailtype(type = 'text')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'text' | - |


##### set_wordwrap(wordwrap = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| wordwrap | None | TRUE | - |


##### set_protocol(protocol = 'mail')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| protocol | None | 'mail' | - |


##### set_priority(n = 3)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | 3 | - |


##### set_newline(newline = "\n")

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| newline | None | "\n" | - |


##### set_crlf(crlf = "\n")

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| crlf | None | "\n" | - |


##### _get_message_id()


##### _get_protocol()


##### _get_encoding()


##### _get_content_type()


##### _set_date()


##### _get_mime_message()


##### validate_email(email)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| email | None | - | - |


##### valid_email(email)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| email | None | - | - |


##### clean_email(email)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| email | None | - | - |


##### _get_alt_message()


##### word_wrap(str, charlim = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| charlim | None | NULL | - |


##### _build_headers()


##### _write_headers()


##### _build_message()


##### _attachments_have_multipart(type)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | - | - |


##### _append_attachments(&$body, boundary, multipart = null)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$body | None | - | - |
| boundary | None | - | - |
| multipart | None | null | - |


##### _prep_quoted_printable(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### _prep_q_encoding(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### send(auto_clear = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| auto_clear | None | TRUE | - |


##### batch_bcc_send()


##### _unwrap_specials()


##### _remove_nl_callback(matches)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| matches | None | - | - |


##### _spool_email()


##### _validate_email_for_shell(&$email)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$email | None | - | - |


##### _send_with_mail()


##### _send_with_sendmail()


##### _send_with_smtp()


##### _smtp_end()


##### _smtp_connect()


##### _send_command(cmd, data = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| cmd | None | - | - |
| data | None | '' | - |


##### _smtp_authenticate()


##### _send_data(data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |


##### _get_smtp_data()


##### _get_hostname()


##### print_debugger(include = array('headers', 'subject', 'body')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| include | None | array('headers' | - |
| 'subject' | None | - | - |
| 'body' | None | - | - |


##### _set_error_message(msg, val = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| msg | None | - | - |
| val | None | '' | - |


##### _mime_types(ext = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| ext | None | '' | - |


##### __destruct()


##### strlen(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### substr(str, start, length = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| start | None | - | - |
| length | None | NULL | - |




## Functions

### __construct(config: array = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | array | array( | - |

**Returns**: (none)



### initialize(config: array = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | array | array( | - |

**Returns**: (none)



### clear(clear_attachments = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| clear_attachments | None | FALSE | - |

**Returns**: (none)



### from(from, name = '', return_path = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| from | None | - | - |
| name | None | '' | - |
| return_path | None | NULL | - |

**Returns**: (none)



### reply_to(replyto, name = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| replyto | None | - | - |
| name | None | '' | - |

**Returns**: (none)



### to(to)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| to | None | - | - |

**Returns**: (none)



### cc(cc)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| cc | None | - | - |

**Returns**: (none)



### bcc(bcc, limit = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| bcc | None | - | - |
| limit | None | '' | - |

**Returns**: (none)



### subject(subject)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| subject | None | - | - |

**Returns**: (none)



### message(body)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| body | None | - | - |

**Returns**: (none)



### attach(file, disposition = '', newname = NULL, mime = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| file | None | - | - |
| disposition | None | '' | - |
| newname | None | NULL | - |
| mime | None | '' | - |

**Returns**: (none)



### attachment_cid(filename)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filename | None | - | - |

**Returns**: (none)



### set_header(header, value)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| header | None | - | - |
| value | None | - | - |

**Returns**: (none)



### _str_to_array(email)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| email | None | - | - |

**Returns**: (none)



### set_alt_message(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### set_mailtype(type = 'text')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'text' | - |

**Returns**: (none)



### set_wordwrap(wordwrap = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| wordwrap | None | TRUE | - |

**Returns**: (none)



### set_protocol(protocol = 'mail')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| protocol | None | 'mail' | - |

**Returns**: (none)



### set_priority(n = 3)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | 3 | - |

**Returns**: (none)



### set_newline(newline = "\n")

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| newline | None | "\n" | - |

**Returns**: (none)



### set_crlf(crlf = "\n")

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| crlf | None | "\n" | - |

**Returns**: (none)



### _get_message_id()

**Returns**: (none)



### _get_protocol()

**Returns**: (none)



### _get_encoding()

**Returns**: (none)



### _get_content_type()

**Returns**: (none)



### _set_date()

**Returns**: (none)



### _get_mime_message()

**Returns**: (none)



### validate_email(email)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| email | None | - | - |

**Returns**: (none)



### valid_email(email)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| email | None | - | - |

**Returns**: (none)



### clean_email(email)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| email | None | - | - |

**Returns**: (none)



### _get_alt_message()

**Returns**: (none)



### word_wrap(str, charlim = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| charlim | None | NULL | - |

**Returns**: (none)



### _build_headers()

**Returns**: (none)



### _write_headers()

**Returns**: (none)



### _build_message()

**Returns**: (none)



### _attachments_have_multipart(type)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | - | - |

**Returns**: (none)



### _append_attachments(&$body, boundary, multipart = null)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$body | None | - | - |
| boundary | None | - | - |
| multipart | None | null | - |

**Returns**: (none)



### _prep_quoted_printable(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### _prep_q_encoding(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### send(auto_clear = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| auto_clear | None | TRUE | - |

**Returns**: (none)



### batch_bcc_send()

**Returns**: (none)



### _unwrap_specials()

**Returns**: (none)



### _remove_nl_callback(matches)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| matches | None | - | - |

**Returns**: (none)



### _spool_email()

**Returns**: (none)



### _validate_email_for_shell(&$email)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$email | None | - | - |

**Returns**: (none)



### _send_with_mail()

**Returns**: (none)



### _send_with_sendmail()

**Returns**: (none)



### _send_with_smtp()

**Returns**: (none)



### _smtp_end()

**Returns**: (none)



### _smtp_connect()

**Returns**: (none)



### _send_command(cmd, data = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| cmd | None | - | - |
| data | None | '' | - |

**Returns**: (none)



### _smtp_authenticate()

**Returns**: (none)



### _send_data(data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |

**Returns**: (none)



### _get_smtp_data()

**Returns**: (none)



### _get_hostname()

**Returns**: (none)



### print_debugger(include = array('headers', 'subject', 'body')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| include | None | array('headers' | - |
| 'subject' | None | - | - |
| 'body' | None | - | - |

**Returns**: (none)



### _set_error_message(msg, val = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| msg | None | - | - |
| val | None | '' | - |

**Returns**: (none)



### _mime_types(ext = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| ext | None | '' | - |

**Returns**: (none)



### __destruct()

**Returns**: (none)



### strlen(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### substr(str, start, length = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| start | None | - | - |
| length | None | NULL | - |

**Returns**: (none)


