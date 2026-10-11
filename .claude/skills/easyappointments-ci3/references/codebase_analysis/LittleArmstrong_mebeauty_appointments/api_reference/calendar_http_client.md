# API Reference: calendar_http_client.js

**Language**: JavaScript

**Source**: `assets/js/http/calendar_http_client.js`

---

## Functions

### saveAppointment(appointment, customer, successCallback, errorCallback, notifyUsers = true, forceSave = false)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | None | - | - |
| customer | None | - | - |
| successCallback | None | - | - |
| errorCallback | None | - | - |
| notifyUsers | None | true | - |
| forceSave | None | false | - |

**Returns**: (none)



### deleteAppointment(appointmentId, cancellationReason, notifyUsers = true)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointmentId | None | - | - |
| cancellationReason | None | - | - |
| notifyUsers | None | true | - |

**Returns**: (none)



### saveUnavailability(unavailability, successCallback, errorCallback)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | None | - | - |
| successCallback | None | - | - |
| errorCallback | None | - | - |

**Returns**: (none)



### deleteUnavailability(unavailabilityId)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailabilityId | None | - | - |

**Returns**: (none)



### saveWorkingPlanException(workingPlanException, providerId, successCallback, errorCallback)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| workingPlanException | None | - | - |
| providerId | None | - | - |
| successCallback | None | - | - |
| errorCallback | None | - | - |

**Returns**: (none)



### deleteWorkingPlanException(exceptionId, providerId, successCallback, errorCallback)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| exceptionId | None | - | - |
| providerId | None | - | - |
| successCallback | None | - | - |
| errorCallback | None | - | - |

**Returns**: (none)



### getCalendarAppointments(recordId, startDate, endDate, filterType)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| recordId | None | - | - |
| startDate | None | - | - |
| endDate | None | - | - |
| filterType | None | - | - |

**Returns**: (none)



### getCalendarAppointmentsForTableView(startDate, endDate)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| startDate | None | - | - |
| endDate | None | - | - |

**Returns**: (none)



### saveAppointmentWithConflictHandling(appointment, customer, successCallback, errorCallback, notifyUsers, revertCallback)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | None | - | - |
| customer | None | - | - |
| successCallback | None | - | - |
| errorCallback | None | - | - |
| notifyUsers | None | - | - |
| revertCallback | None | - | - |

**Returns**: (none)



### attemptSave(forceSave = false)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| forceSave | None | false | - |

**Returns**: (none)


