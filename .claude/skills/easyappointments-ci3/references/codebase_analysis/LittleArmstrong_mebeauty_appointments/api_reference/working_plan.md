# API Reference: working_plan.js

**Language**: JavaScript

**Source**: `assets/js/utils/working_plan.js`

---

## Classes

### WorkingPlan

**Inherits from**: (none)

#### Methods

##### setup(workingPlan)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| workingPlan | None | - | - |


##### each(workingPlanSorted, function (index, workingDay)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| workingPlanSorted | None | - | - |
| function (index | None | - | - |
| workingDay | None | - | - |


##### sort(function (break1, break2)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| function (break1 | None | - | - |
| break2 | None | - | - |


##### forEach(function (workingDayBreak)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| function (workingDayBreak | None | - | - |


##### setupWorkingPlanExceptions(workingPlanExceptions)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| workingPlanExceptions | None | - | - |


##### editableDayCell($selector)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| $selector | None | - | - |


##### editable(function (value)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| function (value | None | - | - |


##### editableTimeCell($selector)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| $selector | None | - | - |


##### editable(function (value)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| function (value | None | - | - |


##### renderWorkingPlanExceptionRow(workingPlanException)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| workingPlanException | None | - | - |


##### addEventListeners()


##### each(function (index, editable)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| function (index | None | - | - |
| editable | None | - | - |


##### validate()


##### isBreakWithinWorkingHours(breakItem, workingDay)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| breakItem | None | - | - |
| workingDay | None | - | - |


##### filterValidBreaks(breaks, workingDay)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| breaks | None | - | - |
| workingDay | None | - | - |


##### removeInvalidBreaksFromDOM(dayId, workingDay)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| dayId | None | - | - |
| workingDay | None | - | - |


##### removeBreaksForNonWorkingDays()


##### cleanupInvalidBreaks()


##### get(autoCleanup = true)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| autoCleanup | None | true | - |


##### getWorkingPlanExceptions()


##### timepickers(disabled)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| disabled | None | - | - |


##### reset()


##### convertValueToDay(value)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| value | None | - | - |


##### convertDayToValue(day)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| day | None | - | - |



