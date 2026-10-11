# API Reference: Form_validation.php

**Language**: PHP

**Source**: `system/libraries/Form_validation.php`

---

## Classes

### CI_Form_validation

**Inherits from**: (none)

#### Methods

##### __construct(rules = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| rules | None | array( | - |


##### set_rules(field, label = null, rules = null, errors = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| label | None | null | - |
| rules | None | null | - |
| errors | None | array( | - |


##### set_data(data: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | array | - | - |


##### set_message(lang, val = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| lang | None | - | - |
| val | None | '' | - |


##### set_error_delimiters(prefix = '<p>', suffix = '</p>')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| prefix | None | '<p>' | - |
| suffix | None | '</p>' | - |


##### error(field, prefix = '', suffix = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| prefix | None | '' | - |
| suffix | None | '' | - |


##### error_array()


##### error_string(prefix = '', suffix = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| prefix | None | '' | - |
| suffix | None | '' | - |


##### run(config = NULL, &$data = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | NULL | - |
| &$data | None | NULL | - |


##### _prepare_rules(rules)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| rules | None | - | - |


##### _reduce_array(array, keys, i = 0)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| array | None | - | - |
| keys | None | - | - |
| i | None | 0 | - |


##### _reset_data_array(&$data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$data | None | - | - |


##### _execute(row, rules, postdata = NULL, cycles = 0)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| row | None | - | - |
| rules | None | - | - |
| postdata | None | NULL | - |
| cycles | None | 0 | - |


##### _get_error_message(rule, field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| rule | None | - | - |
| field | None | - | - |


##### elseif(isset($this->_error_messages[$rule])

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| isset($this->_error_messages[$rule] | None | - | - |


##### _translate_fieldname(fieldname)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| fieldname | None | - | - |


##### _build_error_msg(line, field = '', param = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| line | None | - | - |
| field | None | '' | - |
| param | None | '' | - |


##### has_rule(field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |


##### set_value(field = '', default = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | '' | - |
| default | None | '' | - |


##### set_select(field = '', value = '', default = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | '' | - |
| value | None | '' | - |
| default | None | FALSE | - |


##### set_radio(field = '', value = '', default = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | '' | - |
| value | None | '' | - |
| default | None | FALSE | - |


##### set_checkbox(field = '', value = '', default = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | '' | - |
| value | None | '' | - |
| default | None | FALSE | - |


##### required(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### regex_match(str, regex)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| regex | None | - | - |


##### matches(str, field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| field | None | - | - |


##### differs(str, field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| field | None | - | - |


##### is_unique(str, field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| field | None | - | - |


##### min_length(str, val)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| val | None | - | - |


##### max_length(str, val)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| val | None | - | - |


##### exact_length(str, val)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| val | None | - | - |


##### valid_url(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### valid_email(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### valid_emails(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### valid_ip(ip, which = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| ip | None | - | - |
| which | None | '' | - |


##### valid_mac(mac)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| mac | None | - | - |


##### alpha(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### alpha_numeric(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### alpha_numeric_spaces(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### alpha_dash(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### numeric(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### integer(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### decimal(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### greater_than(str, min)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| min | None | - | - |


##### greater_than_equal_to(str, min)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| min | None | - | - |


##### less_than(str, max)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| max | None | - | - |


##### less_than_equal_to(str, max)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| max | None | - | - |


##### in_list(value, list)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| value | None | - | - |
| list | None | - | - |


##### is_natural(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### is_natural_no_zero(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### valid_base64(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### prep_url(str = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | '' | - |


##### strip_image_tags(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### encode_php_tags(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### reset_validation()




## Functions

### __construct(rules = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| rules | None | array( | - |

**Returns**: (none)



### set_rules(field, label = null, rules = null, errors = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| label | None | null | - |
| rules | None | null | - |
| errors | None | array( | - |

**Returns**: (none)



### set_data(data: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | array | - | - |

**Returns**: (none)



### set_message(lang, val = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| lang | None | - | - |
| val | None | '' | - |

**Returns**: (none)



### set_error_delimiters(prefix = '<p>', suffix = '</p>')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| prefix | None | '<p>' | - |
| suffix | None | '</p>' | - |

**Returns**: (none)



### error(field, prefix = '', suffix = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| prefix | None | '' | - |
| suffix | None | '' | - |

**Returns**: (none)



### error_array()

**Returns**: (none)



### error_string(prefix = '', suffix = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| prefix | None | '' | - |
| suffix | None | '' | - |

**Returns**: (none)



### run(config = NULL, &$data = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | NULL | - |
| &$data | None | NULL | - |

**Returns**: (none)



### _prepare_rules(rules)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| rules | None | - | - |

**Returns**: (none)



### _reduce_array(array, keys, i = 0)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| array | None | - | - |
| keys | None | - | - |
| i | None | 0 | - |

**Returns**: (none)



### _reset_data_array(&$data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$data | None | - | - |

**Returns**: (none)



### _execute(row, rules, postdata = NULL, cycles = 0)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| row | None | - | - |
| rules | None | - | - |
| postdata | None | NULL | - |
| cycles | None | 0 | - |

**Returns**: (none)



### _get_error_message(rule, field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| rule | None | - | - |
| field | None | - | - |

**Returns**: (none)



### elseif(isset($this->_error_messages[$rule])

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| isset($this->_error_messages[$rule] | None | - | - |

**Returns**: (none)



### _translate_fieldname(fieldname)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| fieldname | None | - | - |

**Returns**: (none)



### _build_error_msg(line, field = '', param = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| line | None | - | - |
| field | None | '' | - |
| param | None | '' | - |

**Returns**: (none)



### has_rule(field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |

**Returns**: (none)



### set_value(field = '', default = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | '' | - |
| default | None | '' | - |

**Returns**: (none)



### set_select(field = '', value = '', default = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | '' | - |
| value | None | '' | - |
| default | None | FALSE | - |

**Returns**: (none)



### set_radio(field = '', value = '', default = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | '' | - |
| value | None | '' | - |
| default | None | FALSE | - |

**Returns**: (none)



### set_checkbox(field = '', value = '', default = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | '' | - |
| value | None | '' | - |
| default | None | FALSE | - |

**Returns**: (none)



### required(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### regex_match(str, regex)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| regex | None | - | - |

**Returns**: (none)



### matches(str, field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| field | None | - | - |

**Returns**: (none)



### differs(str, field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| field | None | - | - |

**Returns**: (none)



### is_unique(str, field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| field | None | - | - |

**Returns**: (none)



### min_length(str, val)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| val | None | - | - |

**Returns**: (none)



### max_length(str, val)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| val | None | - | - |

**Returns**: (none)



### exact_length(str, val)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| val | None | - | - |

**Returns**: (none)



### valid_url(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### valid_email(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### valid_emails(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### valid_ip(ip, which = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| ip | None | - | - |
| which | None | '' | - |

**Returns**: (none)



### valid_mac(mac)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| mac | None | - | - |

**Returns**: (none)



### alpha(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### alpha_numeric(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### alpha_numeric_spaces(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### alpha_dash(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### numeric(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### integer(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### decimal(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### greater_than(str, min)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| min | None | - | - |

**Returns**: (none)



### greater_than_equal_to(str, min)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| min | None | - | - |

**Returns**: (none)



### less_than(str, max)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| max | None | - | - |

**Returns**: (none)



### less_than_equal_to(str, max)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| max | None | - | - |

**Returns**: (none)



### in_list(value, list)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| value | None | - | - |
| list | None | - | - |

**Returns**: (none)



### is_natural(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### is_natural_no_zero(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### valid_base64(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### prep_url(str = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | '' | - |

**Returns**: (none)



### strip_image_tags(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### encode_php_tags(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### reset_validation()

**Returns**: (none)


