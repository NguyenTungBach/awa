<template>
    <b-col>
        <div class="list-shift">
            <div class="list-shift__header">
                <b-row>
                    <b-col
                        cols="12"
                        sm="12"
                        md="8"
                        lg="8"
                        xl="8"
                    >
                        <div class="zone-left">
                            <div class="zone-title">
                                <span
                                    v-if="selectTable === CONSTANT.LIST_SHIFT.SHIFT_TABLE"
                                    class="title-page"
                                >
                                    {{ $t("LIST_SHIFT.TITLE_LIST_SHIFT") }}
                                </span>
                                <span
                                    v-else-if="(selectTable === CONSTANT.LIST_SHIFT.SALARY_TABLE)"
                                    class="title-page"
                                >
                                    {{ $t("LIST_SHIFT.TABLE_SALARY") }}
                                </span>
                                <span
                                    v-else-if="(selectTable === CONSTANT.LIST_SHIFT.COURSE_BASE_TABLE)"
                                    class="title-page"
                                >
                                    {{ $t("LIST_SHIFT.TITLE_COURSE_BASE") }}
                                </span>
                                <span
                                    v-else
                                    class="title-page"
                                >
                                    {{ $t("LIST_SHIFT.TITLE_LIST_SHIFT_PRACTICAL_RECORD_TABLE") }}
                                </span>
                            </div>
                            <div
                                v-show="showControlTime"
                                class="zone-select-week-month"
                            >
                                <b-button-group>
                                    <b-button
                                        :class="activeSelectWeekMonth(CONSTANT.LIST_SHIFT.WEEK)"
                                        @click="onClickSelectWeekMonth(CONSTANT.LIST_SHIFT.WEEK)"
                                    >
                                        {{ $t("LIST_SHIFT.BUTTON_WEEK") }}
                                    </b-button>
                                    <b-button
                                        :class="activeSelectWeekMonth(CONSTANT.LIST_SHIFT.MONTH)"
                                        @click="onClickSelectWeekMonth(CONSTANT.LIST_SHIFT.MONTH)"
                                    >
                                        {{ $t("LIST_SHIFT.BUTTON_MONTH") }}
                                    </b-button>
                                </b-button-group>
                            </div>
                            <div
                                v-show="showControlTime"
                                class="zone-calendar-week"
                            >
                                <CalendarWeek
                                    v-show="selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK"
                                    @value="getselectedYMD"
                                />
                            </div>
                        </div>
                    </b-col>
                    <b-col
                        cols="12"
                        sm="12"
                        md="4"
                        lg="4"
                        xl="4"
                    >
                        <div v-if="selectTable === CONSTANT.LIST_SHIFT.SHIFT_TABLE" class="zone-right">
                            <template v-if="!(isLoading.show)">
                                <div
                                    v-if="hasRole(role) && selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH"
                                    v-show="showControlTime"
                                    class="item-function"
                                    @click="handleClickSendAI()"
                                >
                                    <div class="show-icon">
                                        <i class="fa-solid fa-robot" />
                                    </div>
                                    <div class="show-text">
                                        <span>{{ $t("LIST_SHIFT.BUTTON_SHIFT_CREATION") }}</span>
                                    </div>
                                </div>
                            </template>
                            <div
                                v-if="hasRole(role) && selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH"
                                v-show="showControlTime"
                                class="item-function"
                                @click="handleClickViewLog()"
                            >
                                <div class="show-icon">
                                    <i class="fa-regular fa-circle-exclamation" />
                                </div>
                                <div class="show-text">
                                    <span>{{ $t("LIST_SHIFT.BUTTON_VIEW_LOG") }}</span>
                                </div>
                            </div>
                            <div
                                v-if="(hasRole(role) && selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH)"
                                v-show="showControlTime"
                                class="item-function"
                                @click="handleClickATMTC()"
                            >
                                <div class="show-icon">
                                    <i class="fa-solid fa-arrows-rotate" />
                                </div>
                                <div class="show-text">
                                    <span>{{ $t("LIST_SHIFT.BUTTON_ATMTC") }}</span>
                                </div>
                            </div>
                            <div
                                v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH"
                                class="item-function btn-excel"
                                @click="onExportExcel()"
                            >
                                <div class="show-icon">
                                    <i class="fas fa-file-excel" />
                                </div>
                                <div class="show-text">
                                    <span>{{ $t("LIST_SHIFT.BUTTON_DOWNLOAD_EXCEL") }}</span>
                                </div>
                            </div>
                        </div>

                        <div v-if="(selectTable === CONSTANT.LIST_SHIFT.PRACTICAL_ACHIEVEMENTS_MONTHLY || selectTable === CONSTANT.LIST_SHIFT.SALARY_TABLE)" class="zone-right">
                            <div
                                class="item-function btn-excel"
                                @click="onExportExcel()"
                            >
                                <div class="show-icon">
                                    <i class="fas fa-file-excel" />
                                </div>
                                <div class="show-text">
                                    <span>{{ $t("LIST_SHIFT.BUTTON_DOWNLOAD_EXCEL") }}</span>
                                </div>
                            </div>
                        </div>
                    </b-col>
                </b-row>
            </div>
            <LineGray />
            <div class="list-shift__control">
                <b-row>
                    <b-col>
                        <div v-if="hasRole(role)" class="text-left">
                            <b-button-group class="button-text-left">
                                <b-button
                                    :class="activeSelectTable(CONSTANT.LIST_SHIFT.SHIFT_TABLE)"
                                    @click="onClickSelectTable(CONSTANT.LIST_SHIFT.SHIFT_TABLE)"
                                >
                                    <span v-html="$t('LIST_SHIFT.BUTTON_SHIFT_TABLE')" />
                                </b-button>
                                <b-button
                                    :class="activeSelectTable(CONSTANT.LIST_SHIFT.COURSE_BASE_TABLE)"
                                    @click="onClickSelectTable(CONSTANT.LIST_SHIFT.COURSE_BASE_TABLE)"
                                >
                                    <span v-html="$t('LIST_SHIFT.BUTTON_COURSE_BASE')" />
                                </b-button>
                                <b-button
                                    :class="activeSelectTable(CONSTANT.LIST_SHIFT.PRACTICAL_ACHIEVEMENTS_MONTHLY)"
                                    @click="onClickSelectTable(CONSTANT.LIST_SHIFT.PRACTICAL_ACHIEVEMENTS_MONTHLY)"
                                >
                                    <span v-html="$t('LIST_SHIFT.BUTTON_PRACTICAL_ACHIEVEMENTS_MONTHLY')" />
                                </b-button>
                                <b-button
                                    v-if="false"
                                    :class="activeSelectTable(CONSTANT.LIST_SHIFT.PRACTICAL_PERFORMANCE_BY_CLOSING_DATE)"
                                    @click="onClickSelectTable(CONSTANT.LIST_SHIFT.PRACTICAL_PERFORMANCE_BY_CLOSING_DATE)"
                                >
                                    <span
                                        v-html="$t('LIST_SHIFT.BUTTON_PRACTICAL_PERFORMANCE_BY_CLOSING_DATE')"
                                    />
                                </b-button>
                                <b-button
                                    :class="activeSelectTable(CONSTANT.LIST_SHIFT.SALARY_TABLE)"
                                    @click="onClickSelectTable(CONSTANT.LIST_SHIFT.SALARY_TABLE)"
                                >
                                    <span v-html="$t('LIST_SHIFT.BUTTON_TABLE_SALARY')" />
                                </b-button>
                            </b-button-group>
                        </div>
                    </b-col>
                    <b-col
                        v-if="hasRole(role)"
                        v-show="showControlTime"
                    >
                        <div
                            class="text-right"
                        >
                            <b-button
                                v-if="(selectTable === CONSTANT.LIST_SHIFT.SHIFT_TABLE)"
                                v-show="!isLoading.show"
                                pill
                                class="btn-edit btn-color-active"
                                @click="goToPageListShiftEdit"
                            >
                                {{ $t("LIST_SHIFT.BUTTON_EDIT") }}
                            </b-button>
                            <b-button
                                v-else
                                v-show="!isLoading.show"
                                pill
                                class="btn-edit btn-color-active"
                                @click="goToPageListCourseBaseEdit"
                            >
                                {{ $t("LIST_SHIFT.BUTTON_EDIT") }}
                            </b-button>
                        </div>
                    </b-col>
                </b-row>
            </div>
            <!-- <h6
                v-show="selectTable === CONSTANT.LIST_SHIFT.PRACTICAL_ACHIEVEMENTS_MONTHLY"
            >
                1日〜末日までのデータを表示
            </h6>
            <h6
                v-show="selectTable === CONSTANT.LIST_SHIFT.PRACTICAL_PERFORMANCE_BY_CLOSING_DATE"
            >
                前月21日〜20日までのデータを表示
            </h6> -->
            <div class="list-shift__table">
                <b-overlay
                    :show="isLoading.show"
                    :variant="isLoading.variant"
                    :opacity="isLoading.opacity"
                    :blur="isLoading.blur"
                    :rounded="isLoading.sm"
                >

                    <template #overlay>
                        <b-row>
                            <b-col class="text-center">
                                <span class="text-loading-shift">{{ $t('APP.LOADING') }}</span>
                            </b-col>
                        </b-row>
                        <div class="text-center">
                            <img :src="require('@/assets/images/loading_sp.gif')" alt="Loading" style="width: 50%;">
                        </div>
                    </template>

                    <div class="zone-table">
                        <b-table-simple
                            v-show="selectTable === CONSTANT.LIST_SHIFT.SHIFT_TABLE"
                            :key="`shift-table-${reRender}`"
                            bordered
                            no-border-collapse
                        >
                            <b-thead>
                                <b-tr>
                                    <b-th
                                        :colspan="3"
                                        class="fix-header"
                                    />
                                    <template
                                        v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK"
                                    >
                                        <template v-for="(date, idx) in pickerWeek.listDate">
                                            <b-th
                                                :key="`date-${idx}`"
                                                class="th-show-date"
                                            >
                                                <div>
                                                    {{ date.date }} ({{ getTextDay(date.text) }})
                                                </div>
                                            </b-th>
                                        </template>
                                    </template>
                                    <template
                                        v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH"
                                    >
                                        <template v-for="date in pickerYearMonth.numberDate">
                                            <b-th
                                                :key="`date-${date}`"
                                                class="th-show-date"
                                            >
                                                <div>
                                                    {{ date }} ({{ getTextDay(`${pickerYearMonth.year}-${pickerYearMonth.month}-${date}`) }})
                                                </div>
                                            </b-th>
                                        </template>
                                    </template>
                                </b-tr>
                                <b-tr>
                                    <b-th
                                        class="th-employee-number"
                                        @click="onSortTable('driver_code', 'shiftTable')"
                                    >
                                        {{ $t("LIST_SHIFT.TABLE_DATE_EMPLOYEE_NUMBER") }}
                                        <i
                                            v-if="sortTable.shiftTable.sortBy === 'driver_code' && sortTable.shiftTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.shiftTable.sortBy === 'driver_code' && sortTable.shiftTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th>
                                    <b-th
                                        class="th-type-employee"
                                        @click="onSortTable('flag', 'shiftTable')"
                                    >
                                        {{ $t('LIST_SHIFT.TABLE_FLAG') }}
                                        <i
                                            v-if="sortTable.shiftTable.sortBy === 'flag' && sortTable.shiftTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.shiftTable.sortBy === 'flag' && sortTable.shiftTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th>
                                    <b-th class="th-full-name">
                                        {{ $t("LIST_SHIFT.TABLE_FULL_NAME") }}
                                    </b-th>
                                    <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK">
                                        <template v-for="(date, idx) in pickerWeek.listDate">
                                            <b-th :key="`date-${idx}`">
                                                <div v-if="listCalendar.length">
                                                    {{ listCalendar[idx] || '' }}
                                                </div>
                                            </b-th>
                                        </template>
                                    </template>
                                    <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH">
                                        <template v-for="date in pickerYearMonth.numberDate">
                                            <b-th :key="`date-${date}`">
                                                <span>
                                                    {{ listCalendar[date - 1] || '' }}
                                                </span>
                                            </b-th>
                                        </template>
                                    </template>
                                </b-tr>
                            </b-thead>
                            <b-tbody v-if="listShift.length > 0">
                                <template
                                    v-for="(emp, idx) in listShift"
                                >
                                    <tr :key="`emp-no-${idx + 1}`">
                                        <td class="td-employee-number">
                                            {{ emp.driver_code }}
                                        </td>
                                        <b-td class="td-type-employee">
                                            {{ $t(convertValueToText(optionsTypeDriver, emp.flag)) }}
                                        </b-td>
                                        <td class="td-full-name text-center">
                                            {{ emp.driver_name }}
                                        </td>
                                        <template
                                            v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK"
                                        >
                                            <template v-for="(date, idxDate) in pickerWeek.listDate">
                                                <NodeListShift
                                                    :key="`date-${idxDate}`"
                                                    :idx-component="idxDate + 1"
                                                    :data-node="emp.shift_list[idxDate]"
                                                    :date="date.date"
                                                    :emp-data="emp"
                                                    :driver-code="emp.driver_code"
                                                    :driver-name="emp.driver_name"
                                                    :start-date="emp.start_date"
                                                    :end-date="emp.end_date"
                                                />
                                            </template>
                                        </template>
                                        <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH">
                                            <template v-for="(date, idxDate) in pickerYearMonth.numberDate">
                                                <NodeListShift
                                                    :key="`date-${date}`"
                                                    :idx-component="idx + 1"
                                                    :data-node="emp.shift_list[idxDate]"
                                                    :date="date"
                                                    :emp-data="emp"
                                                    :driver-code="emp.driver_code"
                                                    :driver-name="emp.driver_name"
                                                    :start-date="emp.start_date"
                                                    :end-date="emp.end_date"
                                                />
                                            </template>
                                        </template>
                                    </tr>
                                </template>
                            </b-tbody>
                        </b-table-simple>

                        <!-- Table Course -->
                        <b-table-simple
                            v-show="selectTable === CONSTANT.LIST_SHIFT.COURSE_BASE_TABLE"
                            :key="`course-table-${reRender}`"
                            bordered
                            no-border-collapse
                        >
                            <b-thead>
                                <b-tr>
                                    <b-th
                                        :colspan="3"
                                        class="fix-header"
                                    />
                                    <template
                                        v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK"
                                    >
                                        <template v-for="(date, idx) in pickerWeek.listDate">
                                            <b-th
                                                :key="`date-${idx}`"
                                                class="th-show-date"
                                            >
                                                <div>
                                                    {{ date.date }} ({{ getTextDay(date.text) }})
                                                </div>
                                            </b-th>
                                        </template>
                                    </template>
                                    <template
                                        v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH"
                                    >
                                        <template v-for="date in pickerYearMonth.numberDate">
                                            <b-th
                                                :key="`date-${date}`"
                                                class="th-show-date"
                                            >
                                                <div>
                                                    {{ date }} ({{ getTextDay(`${pickerYearMonth.year}-${pickerYearMonth.month}-${date}`) }})
                                                </div>
                                            </b-th>
                                        </template>
                                    </template>
                                </b-tr>
                                <b-tr>
                                    <b-th
                                        class="th-employee-number"
                                        @click="onSortTableCourse('course_code')"
                                    >
                                        {{ $t("LIST_SHIFT.TABLE_COURSE_COURSE_ID") }}
                                        <i
                                            v-if="sortTableCourse.field === 'course_code' && sortTableCourse.type === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTableCourse.field === 'course_code' && sortTableCourse.type === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th>
                                    <b-th
                                        class="th-type-employee"
                                        @click="onSortTableCourse('group')"
                                    >
                                        {{ $t('LIST_SHIFT.TABLE_COURSE_COURSE_GROUP') }}
                                        <i
                                            v-if="sortTableCourse.field === 'group' && sortTableCourse.type === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTableCourse.field === 'group' && sortTableCourse.type === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th>
                                    <b-th class="th-full-name">
                                        {{ $t("LIST_SHIFT.TABLE_COURSE_COURSE_NAME") }}
                                    </b-th>
                                    <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK">
                                        <template v-for="(date, idx) in pickerWeek.listDate">
                                            <b-th :key="`date-${idx + 1}`">
                                                <div v-if="listCalendar.length">
                                                    {{ listCalendar[idx] || '' }}
                                                </div>
                                            </b-th>
                                        </template>
                                    </template>
                                    <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH">
                                        <template v-for="date in pickerYearMonth.numberDate">
                                            <b-th :key="`date-${date}`">
                                                <span>
                                                    {{ listCalendar[date - 1] || '' }}
                                                </span>
                                            </b-th>
                                        </template>
                                    </template>
                                </b-tr>
                            </b-thead>
                            <b-tbody v-if="listTableCourse.length > 0">
                                <template
                                    v-for="(course, idx) in listTableCourse"
                                >
                                    <tr :key="`course-no-${idx + 1}`">
                                        <td class="td-employee-number">
                                            {{ course.course_code }}
                                        </td>
                                        <b-td class="td-type-employee">
                                            {{ course.group ? course.group : '-' }}
                                        </b-td>
                                        <td class="td-full-name text-center">
                                            {{ course.course_name }}
                                        </td>
                                        <template
                                            v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK"
                                        >
                                            <template v-for="(date, idxDate) in pickerWeek.listDate">
                                                <NodeCourseBase
                                                    :key-render="idxDate"
                                                    :item="course.shift_list[idxDate]"
                                                />
                                            </template>
                                        </template>
                                        <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH">
                                            <template v-for="(date, idxDate) in pickerYearMonth.numberDate">
                                                <NodeCourseBase
                                                    :key-render="idxDate"
                                                    :item="course.shift_list[idxDate]"
                                                />
                                            </template>
                                        </template>
                                    </tr>
                                </template>
                            </b-tbody>
                        </b-table-simple>

                        <!-- Table Salary -->
                        <b-table-simple
                            v-show="(selectTable === CONSTANT.LIST_SHIFT.SALARY_TABLE)"
                            bordered
                            class="table-salary"
                            no-border-collapse
                        >
                            <b-thead>
                                <b-tr>
                                    <b-th class="fix-header" colspan="3" />

                                    <b-th v-for="date in pickerYearMonth.numberDate" :key="`date-${date}`" class="th-show-date">
                                        <div>
                                            {{ date }} ({{ getTextDay(`${pickerYearMonth.year}-${pickerYearMonth.month}-${date}`) }})
                                        </div>
                                    </b-th>

                                    <b-th rowspan="2" class="th-total">
                                        {{ $t("LIST_SHIFT.SALARY_TOTAL") }}
                                    </b-th>
                                </b-tr>

                                <b-tr>
                                    <b-th class="th-employee-number" @click="onSortTable('driver_code', 'salaryTable')">
                                        {{ $t("LIST_SHIFT.TABLE_DRIVER_CODE") }}
                                        <i
                                            v-if="sortTable.salaryTable.sortBy === 'driver_code' && sortTable.salaryTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.salaryTable.sortBy === 'driver_code' && sortTable.salaryTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th>

                                    <b-th class="th-type-employee" @click="onSortTable('flag', 'salaryTable')">
                                        {{ $t("LIST_SHIFT.TABLE_DRIVER_TYPE") }}
                                        <i
                                            v-if="sortTable.salaryTable.sortBy === 'flag' && sortTable.salaryTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.salaryTable.sortBy === 'flag' && sortTable.salaryTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th>

                                    <b-th class="th-full-name">
                                        {{ $t("LIST_SHIFT.TABLE_DRIVER_NAME") }}
                                    </b-th>

                                    <b-th v-for="date in pickerYearMonth.numberDate" :key="`date-${date}`">
                                        <span>
                                            {{ listCalendar[date - 1] || '' }}
                                        </span>
                                    </b-th>

                                </b-tr>
                            </b-thead>

                            <b-tbody>
                                <b-tr v-for="(driverSalary, index) in listSalary" :key="driverSalary.id">
                                    <b-td class="td-employee-number">
                                        {{ driverSalary.driver_code }}
                                    </b-td>

                                    <b-td class="td-type-employee">
                                        {{ $t(convertValueToText(optionsTypeDriver, driverSalary.flag)) }}
                                    </b-td>

                                    <b-td class="td-full-name text-center">
                                        {{ driverSalary.driver_name }}
                                    </b-td>

                                    <b-td v-for="salary in driverSalary.shift_list" :key="`salary-${salary.date}`">
                                        {{ salary.value }}
                                    </b-td>

                                    <b-td>
                                        {{ listTotalSalaryMonth[index].value }}
                                    </b-td>
                                </b-tr>
                            </b-tbody>
                            <b-tbody>
                                <b-tr>
                                    <b-td class="td-total" colspan="3">
                                        <span>
                                            {{ $t("LIST_SHIFT.SALARY_TOTAL") }}
                                        </span>
                                    </b-td>

                                    <b-td v-for="total in listTotalSalaryDay" :key="`total-${total.id}`" class="text-center">
                                        {{ total.value }}
                                    </b-td>
                                </b-tr>
                            </b-tbody>
                        </b-table-simple>

                        <b-table-simple
                            v-show="selectTable === CONSTANT.LIST_SHIFT.PRACTICAL_ACHIEVEMENTS_MONTHLY"
                            bordered
                            class="table-practical-achievements-monthly"
                            no-border-collapse
                        >
                            <b-thead>
                                <b-tr>
                                    <b-th class="text-center driver-code" @click="onSortTablePractical('sortby_code', 'pracitcalAchievementsTable')">
                                        {{ $t("LIST_SHIFT.TABLE_DRIVER_CODE") }}
                                        <i
                                            v-if="sortTable.pracitcalAchievementsTable.sortBy === 'sortby_code' && sortTable.pracitcalAchievementsTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.pracitcalAchievementsTable.sortBy === 'sortby_code' && sortTable.pracitcalAchievementsTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th>
                                    <b-th class="text-center driver-flag" @click="onSortTablePractical('sortby_driver_type', 'pracitcalAchievementsTable')">
                                        {{ $t("LIST_SHIFT.TABLE_DRIVER_TYPE") }}
                                        <i
                                            v-if="sortTable.pracitcalAchievementsTable.sortBy === 'sortby_driver_type' && sortTable.pracitcalAchievementsTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.pracitcalAchievementsTable.sortBy === 'sortby_driver_type' && sortTable.pracitcalAchievementsTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th>
                                    <b-th class="text-center driver-name">
                                        {{ $t("LIST_SHIFT.TABLE_DRIVER_NAME") }}
                                    </b-th>
                                    <!-- <th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_TOTAL_TIME") }}
                                    </th> -->
                                    <b-th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_DRIVING_TIME") }}
                                    </b-th>
                                    <b-th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_OVER_TIME") }}
                                    </b-th>
                                    <b-th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_NUMBER_OF_PAID_HOLIDAYS") }}
                                    </b-th>
                                    <b-th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_WORKING_DAYS") }}
                                    </b-th>
                                    <b-th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_DAY_OFF") }}
                                    </b-th>
                                    <b-th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_PAID_HOLIDAYS") }}
                                    </b-th>
                                    <b-th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_ONE_DAY_MAX_TOTAL_TIME") }}
                                    </b-th>
                                    <b-th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_ONE_DAY_MAX_DRIVING_TIME") }}
                                    </b-th>
                                    <b-th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_FIFTEEN_HOURS_OVER_WORKING_DAYS") }}
                                    </b-th>
                                </b-tr>
                            </b-thead>
                            <b-tbody>
                                <template v-for="(driver, idxDriver) in listPractical">
                                    <b-tr :key="`row-${idxDriver + 1}`">
                                        <b-td class="driver-code">
                                            {{ driver.driver_code }}
                                        </b-td>
                                        <b-td class="driver-flag">
                                            {{ $t(convertValueToText(optionsTypeDriver, driver.flag)) }}
                                        </b-td>
                                        <b-td class="driver-name text-center">
                                            {{ driver.driver_name }}
                                        </b-td>
                                        <!-- <b-td>
                                            {{ driver.reports.days_can_work }}
                                        </b-td> -->
                                        <b-td>
                                            {{ driver.reports.working_days }}
                                        </b-td>
                                        <b-td>
                                            {{ driver.reports.days_off }}
                                        </b-td>
                                        <b-td>
                                            {{ driver.reports.days_off_paid }}
                                        </b-td>
                                        <b-td>
                                            {{ driver.reports.days_off_request }}
                                        </b-td>
                                        <b-td>
                                            {{ driver.reports.total_time }}
                                        </b-td>
                                        <b-td>
                                            {{ driver.reports.driving_time }}
                                        </b-td>
                                        <b-td>
                                            {{ driver.reports.break_time }}
                                        </b-td>
                                        <b-td>
                                            {{ driver.reports.over_time }}
                                        </b-td>
                                        <b-td>
                                            {{ driver.reports.point }}
                                        </b-td>
                                    </b-tr>
                                </template>
                                <template v-if="listPractical.length === 0">
                                    <b-tr>
                                        <b-td :colspan="11" class="table-empty ">
                                            <div class="text-center">
                                                {{ $t('APP.TABLE_NO_DATA') }}
                                            </div>
                                        </b-td>
                                    </b-tr>
                                </template>
                            </b-tbody>
                        </b-table-simple>
                        <b-table-simple
                            v-show="selectTable === CONSTANT.LIST_SHIFT.PRACTICAL_PERFORMANCE_BY_CLOSING_DATE"
                            bordered
                            class="table-practical-performance-by-closing-date"
                            no-border-collapse
                        >
                            <b-thead>
                                <b-tr>
                                    <th class="text-center driver-code" @click="onSortTablePractical('sortby_code', 'pracitcalPerformanceTable')">
                                        {{ $t("LIST_SHIFT.TABLE_DRIVER_CODE") }}
                                        <i
                                            v-if="sortTable.pracitcalPerformanceTable.sortBy === 'sortby_code' && sortTable.pracitcalPerformanceTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.pracitcalPerformanceTable.sortBy === 'sortby_code' && sortTable.pracitcalPerformanceTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </th>
                                    <th class="text-center driver-flag" @click="onSortTablePractical('sortby_driver_type', 'pracitcalPerformanceTable')">
                                        {{ $t("LIST_SHIFT.TABLE_DRIVER_TYPE") }}
                                        <i
                                            v-if="sortTable.pracitcalPerformanceTable.sortBy === 'sortby_driver_type' && sortTable.pracitcalPerformanceTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.pracitcalPerformanceTable.sortBy === 'sortby_driver_type' && sortTable.pracitcalPerformanceTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </th>
                                    <th class="text-center driver-name">
                                        {{ $t("LIST_SHIFT.TABLE_DRIVER_NAME") }}
                                    </th>
                                    <!-- <th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_TOTAL_TIME") }}
                                    </th> -->
                                    <th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_DRIVING_TIME") }}
                                    </th>
                                    <th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_OVER_TIME") }}
                                    </th>
                                    <th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_NUMBER_OF_PAID_HOLIDAYS") }}
                                    </th>
                                    <th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_WORKING_DAYS") }}
                                    </th>
                                    <th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_DAY_OFF") }}
                                    </th>
                                    <th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_PAID_HOLIDAYS") }}
                                    </th>
                                    <th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_ONE_DAY_MAX_TOTAL_TIME") }}
                                    </th>
                                    <th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_ONE_DAY_MAX_DRIVING_TIME") }}
                                    </th>
                                    <th class="text-center">
                                        {{ $t("LIST_SHIFT.TABLE_FIFTEEN_HOURS_OVER_WORKING_DAYS") }}
                                    </th>
                                </b-tr>
                            </b-thead>
                            <b-tbody>
                                <template v-for="(driver, idxDriver) in listPractical">
                                    <b-tr :key="`row-${idxDriver + 1}`">
                                        <b-td class="driver-code">
                                            {{ driver.driver_code }}
                                        </b-td>
                                        <b-td class="driver-flag">
                                            {{ $t(convertValueToText(optionsTypeDriver, driver.flag)) }}
                                        </b-td>
                                        <b-td class="driver-name text-center">
                                            {{ driver.driver_name }}
                                        </b-td>
                                        <!-- <b-td>
                                            {{ driver.reports.days_can_work }}
                                        </b-td> -->
                                        <b-td>
                                            {{ driver.reports.working_days }}
                                        </b-td>
                                        <b-td>
                                            {{ driver.reports.days_off }}
                                        </b-td>
                                        <b-td>
                                            {{ driver.reports.days_off_paid }}
                                        </b-td>
                                        <b-td>
                                            {{ driver.reports.days_off_request }}
                                        </b-td>
                                        <b-td>
                                            {{ driver.reports.total_time }}
                                        </b-td>
                                        <b-td>
                                            {{ driver.reports.driving_time }}
                                        </b-td>
                                        <b-td>
                                            {{ driver.reports.break_time }}
                                        </b-td>
                                        <b-td>
                                            {{ driver.reports.over_time }}
                                        </b-td>
                                        <b-td>
                                            {{ driver.reports.point }}
                                        </b-td>
                                    </b-tr>
                                </template>
                                <template v-if="listPractical.length === 0">
                                    <b-tr>
                                        <b-td :colspan="11" class="table-empty ">
                                            <div class="text-center">
                                                {{ $t('APP.TABLE_NO_DATA') }}
                                            </div>
                                        </b-td>
                                    </b-tr>
                                </template>
                            </b-tbody>
                        </b-table-simple>
                    </div>
                </b-overlay>
            </div>
        </div>
        <b-modal
            id="modal-ai"
            v-model="showModalAI"
            body-class="modal-body-ai"
            hide-footer
            no-close-on-backdrop
            static
            @close="handleCloseModalAI"
        >
            <div class="text-center select-type-create-shift">
                <div class="title-select-type-create-shift">
                    <h4>
                        シフト表生成期間を選択してください。
                    </h4>
                </div>
                <div
                    class="item-type-create-shift"
                    @click="onSelectTypeCreateShift('MONTH')"
                >
                    {{ pickerYearMonth.textFull }}
                </div>

                <div
                    class="item-type-create-shift"
                    @click="onSelectTypeCreateShift('DAY')"
                >
                    期間を選択
                </div>
            </div>
        </b-modal>

        <b-modal
            id="modal-confirm-ai-month"
            v-model="showModalConfirmAIOfMonth"
            body-class="modal-body-confirm-ai-month"
            hide-header
            hide-footer
            no-close-on-esc
            no-close-on-backdrop
            static
            @close="handleCloseModalAI"
        >
            <div class="text-center body-item">
                <h5 class="font-weight-bold">
                    {{ `${pickerYearMonth.year}年${pickerYearMonth.month}月のシフト表を` }}
                </h5>
                <h5 class="font-weight-bold">
                    {{ `生成してよろしいですか？` }}
                </h5>
            </div>
            <div class="text-center">
                <b-button
                    pill
                    class="mr-2 btn-modal"
                    @click="handleCloseModalAIMonth()"
                >
                    キャンセル
                </b-button>

                <b-button
                    pill
                    class="btn-color-active btn-modal"
                    @click="handleSubmitSendAI()"
                >
                    OK
                </b-button>
            </div>
        </b-modal>

        <b-modal
            id="modal-confirm-ai-day"
            v-model="showModalConfirmAIOfDay"
            body-class="modal-body-confirm-ai-day"
            hide-header
            hide-footer
            no-close-on-esc
            no-close-on-backdrop
            static
            @close="handleCloseModalAI"
        >
            <div class="body-item">
                <div class="zone-calendar-multiple-week">
                    <CalendarMonth @change="onChangeSelectedCalendarMonth" />
                </div>
            </div>

            <div class="text-center">
                <b-button
                    pill
                    class="mr-2 btn-modal"
                    @click="handleCloseModalAIDay()"
                >
                    キャンセル
                </b-button>

                <b-button
                    pill
                    class="btn-color-active btn-modal"
                    @click="handleSubmitSendAI()"
                >
                    OK
                </b-button>
            </div>
        </b-modal>

        <b-modal
            id="modal-confirm-atmtc"
            v-model="showModalATMTC"
            body-class="modal-body-confirm-atmtc"
            hide-header
            hide-footer
            no-close-on-esc
            no-close-on-backdrop
            static
            @close="handleCloseATMTC"
        >
            <div class="body-item">
                <div class="zone-calendar-multiple-week">
                    <CalendarFreeMonth @change="calendarFreeMonthChange" />
                </div>
            </div>

            <div class="text-center">
                <b-button
                    pill
                    class="mr-2 btn-modal"
                    @click="handleCloseATMTC()"
                >
                    キャンセル
                </b-button>

                <b-button
                    pill
                    class="btn-color-active btn-modal"
                    @click="handleSubmitSendATMTC()"
                >
                    OK
                </b-button>
            </div>
        </b-modal>

        <b-modal
            id="modal-confirm-ai"
            v-model="showModalConfirmAI"
            body-class="modal-body-confirm-ai"
            hide-header
            no-close-on-esc
            no-close-on-backdrop
            static
        >
            <span>{{ $t('LIST_SHIFT.MODAL_CONFIRM_AI', showMessageCheckDuplicate ) }}</span>

            <template #modal-footer>
                <b-button
                    pill
                    class="mr-2 btn-modal"
                    @click="handleCloseModalConfirmAI()"
                >
                    {{ $t('LIST_SHIFT.MODAL_CONFIRM_AI_CANCEL') }}
                </b-button>

                <b-button
                    pill
                    class="btn-color-active btn-modal"
                    @click="handleSubmitConfirmAI()"
                >
                    {{ $t('LIST_SHIFT.MODAL_CONFIRM_AI_OK') }}
                </b-button>
            </template>
        </b-modal>

        <b-modal
            id="modal-log-ai"
            v-model="showModalLogAI"
            :title="$t('LIST_SHIFT.MODAL_TITLE_VIEW_LOG')"
            body-class="modal-body-log-ai"
            footer-class="modal-footer-log-ai"
            hide-header-close
            no-close-on-esc
            no-close-on-backdrop
            static
            show-empty
            scrollable
            size="xl"
        >
            <template #default>
                <b-table
                    bordered
                    :fields="fieldsLogAI"
                    :items="listLogAI"
                    :busy="isLoadingLog"
                >
                    <template #cell(no)="no">
                        {{ (no.index + 1) + ((paginationLogAI.current_page - 1) * 15) }}
                    </template>

                    <template #cell(status)="status">
                        <div
                            :class="{
                                'duck-badge': true,
                                'duck-badge-on': status.item.status === 'on',
                                'duck-badge-error': status.item.status === 'error',
                                'duck-badge-success': status.item.status === 'success',
                                'duck-badge-check': status.item.status === 'check',
                            }"
                            @click="onClickViewDetailLogAI(status)"
                        >
                            {{ $t(`STATUS.${status.item.status}`) }}
                        </div>
                    </template>

                    <template #cell(message)="message">
                        <span
                            :class="{
                                'text-message': true,
                                'text-message-on': message.item.status === 'on',
                                'text-message-error': message.item.status === 'error',
                                'text-message-success': message.item.status === 'success',
                                'text-message-check': message.item.status === 'check',
                            }"
                        >{{ $t(convertStatusToText(message.item.status)) }}</span>
                    </template>

                    <template #empty>
                        <div class="text-center">
                            {{ $t('APP.TABLE_NO_DATA') }}
                        </div>
                    </template>

                    <template #table-busy>
                        <div class="text-center my-2">
                            <b-spinner class="align-middle" />
                        </div>
                    </template>
                </b-table>

                <b-row v-if="paginationLogAI.total_records > paginationLogAI.per_page">
                    <b-col>
                        <b-pagination
                            v-model="paginationLogAI.current_page"
                            pills
                            :total-rows="paginationLogAI.total_records"
                            size="sm"
                            align="right"
                        />
                    </b-col>
                </b-row>
            </template>

            <template #modal-footer>
                <b-button @click="onCloseViewLog()">
                    {{ $t('APP.TEXT_CLOSE') }}
                </b-button>
            </template>
        </b-modal>

        <b-modal
            id="modal-detail-log-ai"
            v-model="showModalDetailLogAI"
            :title="$t('LIST_SHIFT.MODAL_TITLE_DETAIL_VIEW_LOG')"
            body-class="modal-body-detail-log-ai"
            footer-class="modal-footer-detail-log-ai"
            hide-header-close
            no-close-on-esc
            no-close-on-backdrop
            static
            show-empty
            scrollable
            size="lg"
            centered
        >
            <template #default>
                <div v-if="Array.isArray(listDetailLogAI)" class="show-list-message">
                    <div class="text-right">
                        <p class="text-total">
                            {{ $t('LIST_SHIFT.TEXT_TOTAL_MESSAGE', { total: listDetailLogAI.length }) }}
                        </p>
                    </div>

                    <div class="content-list-message">
                        <div
                            v-for="(message, idxMessage) in listDetailLogAI"
                            :id="`message-${idxMessage + 1}`"
                            :key="`message-${idxMessage + 1}`"
                        >
                            <span class="message-error">{{ message }}</span>
                        </div>
                    </div>
                </div>
            </template>

            <template #modal-footer>
                <b-button @click="onCloseDetailViewLog()">
                    {{ $t('APP.TEXT_CLOSE') }}
                </b-button>
            </template>
        </b-modal>
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import { hasRole } from '@/utils/hasRole';
import LineGray from '@/components/LineGray';
import { setLoading } from '@/utils/handleLoading';
import { format2Digit } from '@/utils/generateTime';
import CalendarWeek from '@/components/CalendarWeek';
import { getCalendar } from '@/api/modules/calendar';
import NodeListShift from '@/components/NodeListShift';
import NodeCourseBase from '@/components/NodeCourseBase';
import { convertValueToText } from '@/utils/handleSelect';
import { getListPractical, getListShift, getCheckDataResult, postAddListShift, getListMessageResponseAI } from '@/api/modules/shiftManagement';
import { getTextDayInWeek, getTextDay } from '@/utils/convertTime';
import { cleanObject } from '@/utils/handleObject';
import { getToken } from '@/utils/handleToken';
import { convertValueWhenNull, convertStatusToText } from '@/utils/handleListShift';
import CalendarMultipleWeek from '@/components/CalendarMultipleWeek';
import CalendarMonth from '@/components/CalendarMonth';
import Notification from '@/toast/notification';
import CalendarFreeMonth from '@/components/CalendarFreeMonth';

export default {
	name: 'ListShift',
	components: {
		LineGray,
		NodeListShift,
		CalendarWeek,
		CalendarMultipleWeek,
		CalendarMonth,
		CalendarFreeMonth,
		NodeCourseBase,
	},

	data() {
		return {
			CONSTANT,
			hasRole,
			showLoadingBarTab: false,
			showModalAI: false,
			selectWeekMonth: this.$store.getters.weekOrMonthListShift || CONSTANT.LIST_SHIFT.WEEK,
			selectTable: this.$store.getters.tableListShift || CONSTANT.LIST_SHIFT.SHIFT_TABLE,
			showControlTime: true,
			pickerWeekCreateShift: {
				start: null,
				end: null,
			},

			pickerWeek: {
				start: {
					year: null,
					month: null,
					date: null,
					text: '',
				},

				end: {
					year: null,
					month: null,
					date: null,
					text: '',
				},

				listDate: [],
			},

			listCalendar: [],
			listShift: [],
			listTableCourse: [],
			listSalary: [],

			listPractical: [],

			sortTable: {
				shiftTable: {
					sortBy: '',
					sortType: null,
				},

				salaryTable: {
					sortBy: '',
					sortType: null,
				},

				pracitcalAchievementsTable: {
					sortBy: '',
					sortType: '',
				},

				pracitcalPerformanceTable: {
					sortBy: '',
					sortType: '',
				},
			},

			sortTableCourse: {
				field: '',
				type: '',
			},

			reRender: 0,

			optionsTypeDriver: CONSTANT.LIST_DRIVER.LIST_FLAG,

			showModalConfirmAI: false,
			typeCreateShift: null,
			showModalConfirmAIOfMonth: false,
			showModalConfirmAIOfDay: false,
			selectedDayCreateShift: {
				start: null,
				end: null,
			},

			isCheckAi: null,
			isAiResult: false,

			showModalLogAI: false,
			listLogAI: [],
			isLoadingLog: false,
			paginationLogAI: {
				current_page: 1,
				per_page: 15,
				total_records: 0,
			},

			showModalDetailLogAI: false,
			listDetailLogAI: [],

			showModalATMTC: false,
			pickerTimeATMTC: {
				start: null,
				end: null,
			},

			listTotalSalaryDay: [],
			listTotalSalaryMonth: [],
		};
	},

	computed: {
		role() {
			return this.$store.getters.profile.role;
		},

		pickerYearMonth() {
			return this.$store.getters.pickerYearMonth;
		},

		language() {
			return this.$store.getters.language;
		},

		isLoading() {
			return this.$store.getters.paddingShift;
		},

		showMessageCheckDuplicate() {
			const PICKER_YEAR_MONTH = this.$store.getters.pickerYearMonth;

			const result = {
				start_year: PICKER_YEAR_MONTH.year,
				start_month: PICKER_YEAR_MONTH.month,
				start_date: '',
				end_year: PICKER_YEAR_MONTH.year,
				end_month: format2Digit(PICKER_YEAR_MONTH.month),
				end_date: format2Digit(PICKER_YEAR_MONTH.numberDate + ''),
			};

			if (this.typeCreateShift === 'MONTH') {
				result.start_date = '01';
			}

			if (this.typeCreateShift === 'DAY') {
				const d = new Date(this.selectedDayCreateShift.start);

				result.start_date = format2Digit(d.getDate());
			}

			return result;
		},

		fieldsLogAI() {
			return [
				{ key: 'no', label: this.$t('LIST_SHIFT.TABLE_TITLE_NO'), thClass: 'th-no' },
				{ key: 'created_at', label: this.$t('LIST_SHIFT.TABLE_EXECUTION_DATE_AND_TIME'), thClass: 'th-execution-date-and-time' },
				{ key: 'start_date', label: this.$t('LIST_SHIFT.TABLE_START_TIME'), thClass: 'th-start-time' },
				{ key: 'end_date', label: this.$t('LIST_SHIFT.TABLE_END_TIME'), thClass: 'th-end-time' },
				{ key: 'status', label: this.$t('LIST_SHIFT.TABLE_STATUS'), thClass: 'th-status' },
				{ key: 'message', label: this.$t('LIST_SHIFT.TABLE_MESSAGE'), thClass: 'th-message' },
			];
		},

		currentPageTableLogChange() {
			return this.paginationLogAI.current_page;
		},

		checkEventReloadTable() {
			return this.$store.getters.reloadTableListShift;
		},
	},

	watch: {
		pickerWeek: {
			async handler() {
				setLoading(true);

				if (this.listCalendar.length) {
					await this.handleGetListCalendar();
					await this.handleGetListShift();
				}

				setLoading(false);
			},

			deep: true,
		},

		selectTable() {
			switch (this.selectTable) {
				case CONSTANT.LIST_SHIFT.SHIFT_TABLE:
					this.showControlTime = true;
					break;
				case CONSTANT.LIST_SHIFT.COURSE_BASE_TABLE:
					this.showControlTime = true;
					break;
				case CONSTANT.LIST_SHIFT.PRACTICAL_ACHIEVEMENTS_MONTHLY:
					this.showControlTime = false;
					break;
				case CONSTANT.LIST_SHIFT.PRACTICAL_PERFORMANCE_BY_CLOSING_DATE:
					this.showControlTime = false;
					break;
				case CONSTANT.LIST_SHIFT.SALARY_TABLE:
					this.showControlTime = false;
					break;
			}
		},

		pickerYearMonth: {
			handler: async function() {
				switch (this.selectTable) {
					case CONSTANT.LIST_SHIFT.SHIFT_TABLE:
						setLoading(true);
						await this.handleGetListCalendar();
						await this.handleGetListShift();
						setLoading(false);

						break;
					case CONSTANT.LIST_SHIFT.PRACTICAL_ACHIEVEMENTS_MONTHLY:
						setLoading(true);
						await this.handleGetListPractical(false);
						setLoading(false);

						break;
					case CONSTANT.LIST_SHIFT.PRACTICAL_PERFORMANCE_BY_CLOSING_DATE:
						setLoading(true);
						await this.handleGetListPractical(true);
						setLoading(false);

						break;
				}
			},

			deep: true,
		},

		sortTable: {
			handler: async function() {
				switch (this.selectTable) {
					case CONSTANT.LIST_SHIFT.PRACTICAL_ACHIEVEMENTS_MONTHLY:
						setLoading(true);
						await this.handleGetListPractical(false);
						await this.handleGetListShift();
						setLoading(false);

						break;
					case CONSTANT.LIST_SHIFT.PRACTICAL_PERFORMANCE_BY_CLOSING_DATE:
						setLoading(true);
						await this.handleGetListPractical(true);
						setLoading(false);

						break;
				}
			},

			deep: true,
		},

		currentPageTableLogChange() {
			if (this.showModalLogAI) {
				this.handleClickViewLog();
			}
		},

		checkEventReloadTable() {
			this.initData();
		},
	},

	created() {
		this.initData();
	},

	methods: {
		convertValueToText,
		convertStatusToText,

		onSelectTypeCreateShift(type) {
			if (type === 'MONTH') {
				this.showModalAI = false;
				this.typeCreateShift = 'MONTH';
				this.showModalConfirmAIOfMonth = true;
			}

			if (type === 'DAY') {
				this.showModalAI = false;
				this.typeCreateShift = 'DAY';
				this.showModalConfirmAIOfDay = true;
			}
		},

		handleCloseModalAIMonth() {
			this.typeCreateShift = null;
			this.showModalConfirmAIOfMonth = false;
		},

		handleCloseModalAIDay() {
			this.typeCreateShift = null;
			this.showModalConfirmAIOfDay = false;
		},

		onChangeSelectedCalendarMonth(value) {
			this.selectedDayCreateShift = value;
		},

		async initData() {
			const TYPE = this.$store.getters.weekOrMonthListShift || CONSTANT.LIST_SHIFT.MONTH;

			await this.onClickSelectWeekMonth(TYPE);
			// await this.onClickSelectTable();
		},

		async handleGetListCalendar() {
			try {
				this.listCalendar = [];

				let START_DATE = '';
				let END_DATE = '';

				if ([CONSTANT.LIST_SHIFT.SHIFT_TABLE, CONSTANT.LIST_SHIFT.COURSE_BASE_TABLE].includes(this.selectTable)) {
					if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK) {
						START_DATE = this.pickerWeek.listDate[0].text;
						END_DATE = this.pickerWeek.listDate[this.pickerWeek.listDate.length - 1].text;
					}

					if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH) {
						START_DATE = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}-01`;
						END_DATE = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}-${this.pickerYearMonth.numberDate}`;
					}
				}

				if (this.selectTable === CONSTANT.LIST_SHIFT.SALARY_TABLE) {
					START_DATE = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}-01`;
					END_DATE = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}-${this.pickerYearMonth.numberDate}`;
				}

				const CALENDAR = await getCalendar(CONSTANT.URL_API.GET_CALENDAR, {
					start_date: START_DATE,
					end_date: END_DATE,
				});

				if (CALENDAR.code === 200) {
					const len = CALENDAR.data.length;
					let idx = 0;

					while (idx < len) {
						this.listCalendar.push(CALENDAR.data[idx].rokuyou);

						idx++;
					}
				}
			} catch {
				setLoading(false);
			}
		},

		async handleGetListShift() {
			try {
				this.listShift.length = 0;

				const TYPE = this.selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK ? 'week' : 'month';

				let PARAMS = {
					type: TYPE,
				};

				if (this.sortTable.shiftTable.sortBy) {
					PARAMS[this.sortTable.shiftTable.sortBy] = this.sortTable.shiftTable.sortType ? 'asc' : 'desc';
				}

				if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK) {
					const START = this.pickerWeek.start;
					const END = this.pickerWeek.end;

					PARAMS.start_date = `${START.year}-${format2Digit(START.month)}-${format2Digit(START.date)}`;
					PARAMS.end_date = `${END.year}-${format2Digit(END.month)}-${format2Digit(END.date)}`;

					const YEAR = this.pickerYearMonth.year;
					const MONTH = this.pickerYearMonth.month;

					const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

					PARAMS.date = YEAR_MONTH;
				}

				if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH) {
					const YEAR = this.pickerYearMonth.year;
					const MONTH = this.pickerYearMonth.month;

					const START = `${YEAR}-${format2Digit(MONTH)}-01`;
					const END = `${YEAR}-${format2Digit(MONTH)}-${format2Digit(this.pickerYearMonth.numberDate)}`;

					PARAMS.start_date = START;
					PARAMS.end_date = END;

					const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

					PARAMS.date = YEAR_MONTH;
				}

				PARAMS = cleanObject(PARAMS);

				if (PARAMS.field) {
					PARAMS.sortby = PARAMS.sortby ? 'asc' : 'desc';
				}

				const { code, data } = await getListShift(CONSTANT.URL_API.GET_LIST_SHIFT_TABLE, PARAMS);

				if (code === 200) {
					this.listShift = convertValueWhenNull(data);
					this.reloadTable();
				}
			} catch (error) {
				console.log(error);
			}
		},

		async handleGetTableCourse() {
			try {
				this.listTableCourse.length = 0;

				const PARAMS = {
					display: 1,
				};

				if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK) {
					const START = this.pickerWeek.start;
					const END = this.pickerWeek.end;

					PARAMS.start_date = `${START.year}-${format2Digit(START.month)}-${format2Digit(START.date)}`;
					PARAMS.end_date = `${END.year}-${format2Digit(END.month)}-${format2Digit(END.date)}`;
					PARAMS.type = 'week';

					const YEAR = this.pickerYearMonth.year;
					const MONTH = this.pickerYearMonth.month;

					const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

					PARAMS.date = YEAR_MONTH;
				}

				if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH) {
					const YEAR = this.pickerYearMonth.year;
					const MONTH = this.pickerYearMonth.month;

					const START = `${YEAR}-${format2Digit(MONTH)}-01`;
					const END = `${YEAR}-${format2Digit(MONTH)}-${format2Digit(this.pickerYearMonth.numberDate)}`;

					PARAMS.start_date = START;
					PARAMS.end_date = END;
					PARAMS.type = 'month';

					const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

					PARAMS.date = YEAR_MONTH;
				}

				if (this.sortTableCourse.field) {
					PARAMS.field = this.sortTableCourse.field;
					PARAMS.sortby = this.sortTableCourse.type ? 'asc' : 'desc';
				}

				const { code, data } = await getListShift(CONSTANT.URL_API.GET_LIST_SHIFT_TABLE, PARAMS);

				console.log(data);

				if (code === 200) {
					this.listTableCourse = data;
				} else {
					this.listTableCourse = [];
				}
			} catch (error) {
				console.log(error);
			}
		},

		async handleGetTableSalary() {
			try {
				this.listSalary.length = 0;

				const YEAR = this.$store.getters.pickerYearMonth.year;
				const MONTH = this.$store.getters.pickerYearMonth.month;
				const NUMBER_DATE = this.$store.getters.pickerYearMonth.numberDate;

				const START_SALARY = `${YEAR}-${format2Digit(MONTH)}-01`;
				const END_SALARY = `${YEAR}-${format2Digit(MONTH)}-${format2Digit(NUMBER_DATE)}`;
				const MONTH_SALARY = `${YEAR}-${format2Digit(MONTH)}`;

				const PARAMS = {
					start_date: START_SALARY,
					end_date: END_SALARY,
					date: MONTH_SALARY,
					tab3: true,
					type: 'month',
				};

				if (this.sortTable.salaryTable.sortBy) {
					PARAMS.field = this.sortTable.salaryTable.sortBy;
					PARAMS.sortby = this.sortTable.salaryTable.sortType ? 'asc' : 'desc';
				}

				const { code, data } = await getListShift(CONSTANT.URL_API.GET_LIST_SHIFT_TABLE, PARAMS);

				if (code === 200) {
					this.listSalary = data;
					this.convertTotalSalary();
				} else {
					this.listSalary = [];
				}
			} catch (error) {
				this.listSalary = [];
				console.log(error);
			}
		},

		convertTotalSalary() {
			console.log('list SALARY: ', this.listSalary);

			const data = this.listSalary;
			const len = this.listSalary.length;
			const day = this.pickerYearMonth.numberDate;
			let total = 0;
			let totalMonth = 0;
			let dataTotalDay = {};
			let dataTotalMonth = {};
			const salaryTotalDay = [];
			const salaryTotalMonth = [];
			let i = 0;
			let j = 0;

			// console.log('ex data value: ', data[0].shift_list[0].value);

			for (i = 0; i < len; i++) {
				for (j = 0; j < day; j++) {
					totalMonth = totalMonth + data[i].shift_list[j].value;
				}
				dataTotalMonth = {
					id: data[i].id,
					value: totalMonth,
				};
				salaryTotalMonth.push(dataTotalMonth);
				totalMonth = 0;
			}

			i = 0;
			j = 0;

			// console.log('total by month1: ', salaryTotalMonth);

			let idxId = -1;
			for (i = 0; i < day; i++) {
				for (j = 0; j < len; j++) {
					total = total + data[j].shift_list[i].value;
					idxId = j;
				}
				dataTotalDay = {
					id: i,
					value: total,
				};
				idxId = -1;
				salaryTotalDay.push(dataTotalDay);
				total = 0;
			}

			const totalAllMonth = {
				id: day,
				value: 0,
			};
			i = 0;
			j = 0;

			// console.log('ex salaryTotalMonth: ', salaryTotalMonth);

			for (let i = 0; i < salaryTotalMonth.length; i++) {
				totalAllMonth.value = totalAllMonth.value + salaryTotalMonth[i].value;
			}

			salaryTotalDay.push(totalAllMonth);

			this.listTotalSalaryDay = salaryTotalDay;
			this.listTotalSalaryMonth = salaryTotalMonth;
			console.log('total by day 2: ', this.listTotalSalaryDay);
			console.log('total by month 2: ', this.listTotalSalaryMonth);
		},

		async onSortTable(col, table) {
			switch (col) {
				case 'driver_code':
					if (this.sortTable[table].sortBy === 'driver_code') {
						if (this.sortTable[table].sortType) {
							this.sortTable[table].sortType = !this.sortTable[table].sortType;
						} else {
							this.sortTable[table].sortType = true;
						}
					} else {
						this.sortTable[table].sortBy = 'driver_code';
						this.sortTable[table].sortType = true;
					}

					setLoading(true);
					if (this.selectTable === CONSTANT.LIST_SHIFT.SHIFT_TABLE) {
						await this.handleGetListShift();
					}

					if (this.selectTable === CONSTANT.LIST_SHIFT.SALARY_TABLE) {
						await this.handleGetTableSalary();
					}
					setLoading(false);

					break;

				case 'flag':
					if (this.sortTable[table].sortBy === 'flag') {
						if (this.sortTable[table].sortType) {
							this.sortTable[table].sortType = !this.sortTable[table].sortType;
						} else {
							this.sortTable[table].sortType = true;
						}
					} else {
						this.sortTable[table].sortBy = 'flag';
						this.sortTable[table].sortType = true;
					}

					setLoading(true);
					if (this.selectTable === CONSTANT.LIST_SHIFT.SHIFT_TABLE) {
						await this.handleGetListShift();
					}

					if (this.selectTable === CONSTANT.LIST_SHIFT.SALARY_TABLE) {
						await this.handleGetTableSalary();
					}
					setLoading(false);

					break;

				default:
					console.log('Handle sort table faild');

					break;
			}
		},

		reloadTable() {
			this.reRender++;
		},

		getTextDayInWeek,
		getTextDay,
		randomIntFromInterval(min, max) {
			return Math.floor(Math.random() * (max - min + 1) + min);
		},

		handleClickATMTC() {
			this.showModalATMTC = true;
		},

		handleCloseATMTC() {
			this.showModalATMTC = false;
			this.pickerTimeATMTC = {
				start: null,
				end: null,
			};
		},

		calendarFreeMonthChange(time) {
			this.pickerTimeATMTC = time;
		},

		handleSubmitSendATMTC() {
			this.handleCloseATMTC();
		},

		handleClickSendAI() {
			this.showModalAI = true;
		},

		handleCloseModalAI() {
			this.showModalAI = false;
			this.pickerWeekCreateShift = {
				start: null,
				end: null,
			};
		},

		async handleSubmitSendAI() {
			try {
				if (this.typeCreateShift === 'DAY') {
					if (!this.selectedDayCreateShift.start) {
						Notification.warning(this.$t('MESSAGE_APP.LIST_SHIFT_VALIDATE_SELECTED_DATE'));

						return;
					}
				}

				this.showModalConfirmAIOfDay = false;
				this.showModalConfirmAIOfMonth = false;

				const PARAMS_CHECK_DATA_RESULT = {};

				if (this.typeCreateShift === 'MONTH') {
					PARAMS_CHECK_DATA_RESULT.date = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}`;
					PARAMS_CHECK_DATA_RESULT.start_date = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}-01`;
					PARAMS_CHECK_DATA_RESULT.end_date = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}-${format2Digit(this.pickerYearMonth.numberDate)}`;
				}

				if (this.typeCreateShift === 'DAY') {
					PARAMS_CHECK_DATA_RESULT.date = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}`;
					PARAMS_CHECK_DATA_RESULT.start_date = this.selectedDayCreateShift.start;
					PARAMS_CHECK_DATA_RESULT.end_date = this.selectedDayCreateShift.end;
				}

				const CHECK_DATA_RESULT = await getCheckDataResult(CONSTANT.URL_API.POST_CHECK_DATA_RESULT, PARAMS_CHECK_DATA_RESULT);

				if (CHECK_DATA_RESULT.code === 200) {
					if (CHECK_DATA_RESULT.data.message === '選択されたシフトは既に作成されています。上書きしてシフトを作成しますか？') {
						this.showModalAI = false;
						this.showModalConfirmAI = true;
					} else {
						this.showModalAI = false;
						this.handleSubmitConfirmAI();
					}
				}
			} catch (error) {
				console.log(error);
			}
		},

		handleCloseModalConfirmAI() {
			this.showModalConfirmAI = false;
		},

		setPaddingShift(status = true) {
			this.$store.dispatch('loading/setPaddingShift', status);
		},

		async handleSubmitConfirmAI() {
			setLoading(true);

			this.showModalConfirmAI = false;

			if (this.typeCreateShift === 'DAY') {
				if (!this.selectedDayCreateShift.start || !this.selectedDayCreateShift.end) {
					Notification.warning(this.$t('MESSAGE_APP.LIST_SHIFT_VALIDATE_SELECTED_DATE'));

					return;
				}
			}

			this.showModalConfirmAIOfDay = false;
			this.showModalConfirmAIOfMonth = false;

			try {
				const BODY_ADD_LIST_SHIFT = {};

				if (this.typeCreateShift === 'MONTH') {
					BODY_ADD_LIST_SHIFT.date = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}`;
					BODY_ADD_LIST_SHIFT.start_date = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}-01`;
				}

				if (this.typeCreateShift === 'DAY') {
					BODY_ADD_LIST_SHIFT.date = `${this.pickerYearMonth.year}-${format2Digit(this.pickerYearMonth.month)}`;
					BODY_ADD_LIST_SHIFT.start_date = this.selectedDayCreateShift.start;
				}

				const ADD_LIST_SHIFT = await postAddListShift(CONSTANT.URL_API.POST_ADD_LIST_SHIFT, BODY_ADD_LIST_SHIFT);

				if (ADD_LIST_SHIFT.code === 200) {
					setLoading(false);
					this.setPaddingShift(true);
					this.handleCheckAi();
				}
			} catch (error) {
				setLoading(false);
				this.setPaddingShift(false);
				console.log(error);
			}

			setLoading(false);
		},

		handleCheckAi() {
			this.$bus.emit('TOSHIN_CHECK_AI');
		},

		getselectedYMD(value) {
			this.pickerWeek = value;
			this.$store.dispatch('app/setPickerWeek', value);
		},

		getCalendarMultipleWeek(value) {
			this.pickerWeekCreateShift = value;
		},

		goToPageListShiftEdit() {
			this.$router.push({ name: 'ListShiftEdit' });
		},

		goToPageListCourseBaseEdit() {
			this.$router.push({ name: 'ListCourseBaseEdit' });
		},

		activeSelectWeekMonth(type) {
			return this.selectWeekMonth === type
				? 'btn-select-week-month active btn-list-shift-week'
				: 'btn-select-week-month btn-list-shift-month';
		},

		activeSelectTable(table) {
			return this.selectTable === table
				? 'control-button-group active'
				: 'control-button-group';
		},

		async onClickSelectWeekMonth(type) {
			this.listCalendar.length = 0;
			this.listShift.length = 0;

			if (
				[CONSTANT.LIST_SHIFT.WEEK, CONSTANT.LIST_SHIFT.MONTH].includes(type)
			) {
				this.selectWeekMonth = type;
			} else {
				this.selectWeekMonth = this.$store.getters.weekOrMonthListShift || CONSTANT.LIST_SHIFT.MONTH;
			}

			this.$store.dispatch('listShift/setIsWeekOrMonth', this.selectWeekMonth)
				.then(async() => {
					setLoading(true);
					if (this.selectTable === CONSTANT.LIST_SHIFT.SHIFT_TABLE) {
						await this.handleGetListCalendar();
						await this.handleGetListShift();
					}

					if (this.selectTable === CONSTANT.LIST_SHIFT.COURSE_BASE_TABLE) {
						await this.handleGetListCalendar();
						await this.handleGetTableCourse();
					}

					if (hasRole(this.role)) {
						await this.handleGetListPractical(false);
					}

					setLoading(false);
				});
		},

		async onClickSelectTable(table) {
			if (!table) {
				table = CONSTANT.LIST_SHIFT.SHIFT_TABLE;
			}

			if (
				[
					CONSTANT.LIST_SHIFT.SHIFT_TABLE,
					CONSTANT.LIST_SHIFT.COURSE_BASE_TABLE,
					CONSTANT.LIST_SHIFT.PRACTICAL_ACHIEVEMENTS_MONTHLY,
					CONSTANT.LIST_SHIFT.PRACTICAL_PERFORMANCE_BY_CLOSING_DATE,
					CONSTANT.LIST_SHIFT.SALARY_TABLE,
				].includes(table)
			) {
				if (hasRole(this.role)) {
					this.selectTable = table;

					if (table === CONSTANT.LIST_SHIFT.PRACTICAL_ACHIEVEMENTS_MONTHLY) {
						setLoading(true);
						await this.handleGetListPractical(false);
						setLoading(false);
					}

					if (table === CONSTANT.LIST_SHIFT.COURSE_BASE_TABLE) {
						setLoading(true);
						await this.handleGetListCalendar();
						await this.handleGetTableCourse();
						setLoading(false);
					}

					if (table === CONSTANT.LIST_SHIFT.PRACTICAL_PERFORMANCE_BY_CLOSING_DATE) {
						setLoading(true);
						await this.handleGetListPractical(true);
						setLoading(false);
					}

					if (table === CONSTANT.LIST_SHIFT.SALARY_TABLE) {
						setLoading(true);
						await this.handleGetListCalendar();
						await this.handleGetTableSalary();
						setLoading(false);
					}

					if (table === CONSTANT.LIST_SHIFT.SHIFT_TABLE) {
						setLoading(true);
						await this.handleGetListCalendar();
						await this.handleGetListShift();
						setLoading(false);
					}
				} else {
					table = CONSTANT.LIST_SHIFT.SHIFT_TABLE;
					this.selectTable = table;

					setLoading(true);
					await this.handleGetListCalendar();
					await this.handleGetListShift();
					setLoading(false);
				}
			}

			this.$store.dispatch('listShift/setTable', this.selectTable);
		},

		async handleGetListPractical(statusView) {
			try {
				const YEAR = this.pickerYearMonth.year;
				const MONTH = this.pickerYearMonth.month < 10 ? `0${this.pickerYearMonth.month}` : `${this.pickerYearMonth.month}`;
				const YEAR_MONTH = `${YEAR}-${MONTH}`;

				const sortTable = {
					sortBy: '',
					sortType: '',
				};

				if (this.selectTable === CONSTANT.LIST_SHIFT.PRACTICAL_ACHIEVEMENTS_MONTHLY) {
					sortTable.sortBy = this.sortTable.pracitcalAchievementsTable.sortBy;
					sortTable.sortType = this.sortTable.pracitcalAchievementsTable.sortType;
				}

				if (this.selectTable === CONSTANT.LIST_SHIFT.PRACTICAL_PERFORMANCE_BY_CLOSING_DATE) {
					sortTable.sortBy = this.sortTable.pracitcalPerformanceTable.sortBy;
					sortTable.sortType = this.sortTable.pracitcalPerformanceTable.sortType;
				}

				const PARAMS = {
					view_date: YEAR_MONTH,
					status_view: statusView ? 'fix' : null,
					sync: 1,
				};

				if (sortTable.sortBy) {
					PARAMS[sortTable.sortBy] = sortTable.sortType ? 'asc' : 'desc';
				}

				const URL = CONSTANT.URL_API.GET_LIST_PRACTICAL;

				const { code, data } = await getListPractical(URL, PARAMS);

				if (code === 200) {
					// Comment vì reports từ Toshin là Array sang GAC là Object
					// const LIST_PRACTICAL = data.filter((driver) => driver.reports.length >= 1);

					this.listPractical = data;
				} else {
					this.listPractical.length = 0;
				}
			} catch (error) {
				console.log(error);
			}
		},

		async onSortTableCourse(col) {
			switch (col) {
				case 'course_code': {
					if (this.sortTableCourse.field === 'course_code') {
						if (this.sortTableCourse.type) {
							this.sortTableCourse.type = !this.sortTableCourse.type;
						} else {
							this.sortTableCourse.type = true;
						}
					} else {
						this.sortTableCourse.field = 'course_code';
						this.sortTableCourse.type = true;
					}

					break;
				}

				case 'group': {
					if (this.sortTableCourse.field === 'group') {
						if (this.sortTableCourse.type) {
							this.sortTableCourse.type = !this.sortTableCourse.type;
						} else {
							this.sortTableCourse.type = true;
						}
					} else {
						this.sortTableCourse.field = 'group';
						this.sortTableCourse.type = true;
					}

					break;
				}
			}

			setLoading(true);
			await this.handleGetTableCourse();
			setLoading(false);
		},

		onSortTablePractical(col, table) {
			switch (col) {
				case 'sortby_code':
					if (this.sortTable[table].sortBy === 'sortby_code') {
						if (this.sortTable[table].sortType) {
							this.sortTable[table].sortType = !this.sortTable[table].sortType;
						} else {
							this.sortTable[table].sortType = true;
						}
					} else {
						this.sortTable[table].sortBy = 'sortby_code';
						this.sortTable[table].sortType = true;
					}

					break;

				case 'sortby_driver_type':
					if (this.sortTable[table].sortBy === 'sortby_driver_type') {
						if (this.sortTable[table].sortType) {
							this.sortTable[table].sortType = !this.sortTable[table].sortType;
						} else {
							this.sortTable[table].sortType = true;
						}
					} else {
						this.sortTable[table].sortBy = 'sortby_driver_type';
						this.sortTable[table].sortType = true;
					}

					break;

				default:
					console.log('Handle sort table faild');

					break;
			}
		},

		onExportExcel() {
			if (this.selectTable === CONSTANT.LIST_SHIFT.SHIFT_TABLE) {
				const sort = {
					field: this.sortTable.shiftTable.sortBy,
					sortby: this.sortTable.shiftTable.sortType,
				};

				let SORT = '';

				if (sort.field === 'driver_code') {
					if (sort.sortby) {
						SORT = '&sortby_code=asc';
					} else {
						SORT = '&sortby_code=desc';
					}
				}

				if (sort.field === 'flag') {
					if (sort.sortby) {
						SORT = '&sortby_driver_type=asc';
					} else {
						SORT = '&sortby_driver_type=desc';
					}
				}

				const PICKER_YEAR_MONTH = this.$store.getters.pickerYearMonth;
				let START_DATE = '';
				let END_DATE = '';

				if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK) {
					START_DATE = this.pickerWeek.listDate[0].text;
					END_DATE = this.pickerWeek.listDate[this.pickerWeek.listDate.length - 1].text;
				}

				if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH) {
					START_DATE = `${PICKER_YEAR_MONTH.year}-${format2Digit(PICKER_YEAR_MONTH.month)}-01`;
					END_DATE = `${PICKER_YEAR_MONTH.year}-${format2Digit(PICKER_YEAR_MONTH.month)}-${format2Digit(PICKER_YEAR_MONTH.numberDate)}`;
				}

				const DATE = `start_date=${START_DATE}&end_date=${END_DATE}`;

				const NAME_START_DATE = START_DATE.replace(/-+/g, '');
				const NAME_END_DATE = END_DATE.replace(/-+/g, '');

				const NAME_DATE = `${NAME_START_DATE}-${NAME_END_DATE}`;

				const YEAR = this.pickerYearMonth.year || null;
				const MONTH = this.pickerYearMonth.month || null;

				const YEAR_MONTH = `${YEAR}-${MONTH < 10 ? `0${MONTH}` : `${MONTH}`}`;

				const KEY_DATE = `&date=${YEAR_MONTH}`;

				const URL = `/api${CONSTANT.URL_API.GET_EXPORT_SHIFT_EXCEL_ONLY_WEEK}?${DATE}${SORT}${KEY_DATE}`;

				let FILE_DOWNLOAD;

				fetch(URL, {
					headers: {
						'Accept-Language': this.$store.getters.language,
						'Authorization': getToken(),
						'accept': 'application/json',
					},
				}).then(async(res) => {
					let filename = decodeURI(res.headers.get('content-disposition').split(`filename*=utf-8''`)[1]) || `シフト表_{${NAME_DATE}}`;

					filename = filename.replaceAll('"', '');
					await res.blob().then((res) => {
						FILE_DOWNLOAD = res;
					});
					const fileURL = window.URL.createObjectURL(FILE_DOWNLOAD);
					const fileLink = document.createElement('a');

					fileLink.href = fileURL;
					fileLink.setAttribute('download', filename);
					document.body.appendChild(fileLink);

					fileLink.click();
				})
					.catch((err) => {
						console.log(err);
					});
			}
			if (this.selectTable === CONSTANT.LIST_SHIFT.PRACTICAL_ACHIEVEMENTS_MONTHLY) {
				const sort = {
					field: this.sortTable.pracitcalAchievementsTable.sortBy,
					sortby: this.sortTable.pracitcalAchievementsTable.sortType,
				};

				let SORT = '';

				if (sort.field === 'driver_code') {
					if (sort.sortby) {
						SORT = '&sortby_code=asc';
					} else {
						SORT = '&sortby_code=desc';
					}
				}

				if (sort.field === 'flag') {
					if (sort.sortby) {
						SORT = '&sortby_driver_type=asc';
					} else {
						SORT = '&sortby_driver_type=desc';
					}
				}

				const STATUS_VIEW = '&status_view=month';

				const YEAR = this.pickerYearMonth.year || null;
				const MONTH = this.pickerYearMonth.month || null;

				const YEAR_MONTH = `${YEAR}-${MONTH < 10 ? `0${MONTH}` : `${MONTH}`}`;

				let VIEW_DATE = '';

				VIEW_DATE = `view_date=${YEAR_MONTH}` || null;

				const URL = `/api${CONSTANT.URL_API.GET_EXPORT_PRACTICAL_PERFORMANCE_EXCEL}?${VIEW_DATE}${STATUS_VIEW}${SORT}`;

				let FILE_DOWNLOAD;

				fetch(URL, {
					headers: {
						'Accept-Language': this.$store.getters.language,
						'Authorization': getToken(),
						'accept': 'application/json',
					},
				}).then(async(res) => {
					let filename = decodeURI(res.headers.get('content-disposition').split(`filename*=utf-8''`)[1]) || `実務実績月別_{${YEAR_MONTH}}`;
					filename = filename.replaceAll('"', '');
					await res.blob().then((res) => {
						FILE_DOWNLOAD = res;
					});
					const fileURL = window.URL.createObjectURL(FILE_DOWNLOAD);
					const fileLink = document.createElement('a');

					fileLink.href = fileURL;
					fileLink.setAttribute('download', filename);
					document.body.appendChild(fileLink);

					fileLink.click();
				})
					.catch((err) => {
						console.log(err);
					});
			}

			if (this.selectTable === CONSTANT.LIST_SHIFT.PRACTICAL_PERFORMANCE_BY_CLOSING_DATE) {
				const sort = {
					field: this.sortTable.pracitcalPerformanceTable.sortBy,
					sortby: this.sortTable.pracitcalPerformanceTable.sortType,
				};

				let SORT = '';

				if (sort.field === 'driver_code') {
					if (sort.sortby) {
						SORT = '&sortby_code=asc';
					} else {
						SORT = '&sortby_code=desc';
					}
				}

				if (sort.field === 'flag') {
					if (sort.sortby) {
						SORT = '&sortby_driver_type=asc';
					} else {
						SORT = '&sortby_driver_type=desc';
					}
				}

				const STATUS_VIEW = '&status_view=fix';

				const YEAR = this.pickerYearMonth.year || null;
				const MONTH = this.pickerYearMonth.month || null;

				const YEAR_MONTH = `${YEAR}-${MONTH < 10 ? `0${MONTH}` : `${MONTH}`}`;

				let VIEW_DATE = '';

				VIEW_DATE = `view_date=${YEAR_MONTH}` || null;

				const URL = `/api${CONSTANT.URL_API.GET_EXPORT_PRACTICAL_PERFORMANCE_EXCEL}?${VIEW_DATE}${STATUS_VIEW}${SORT}`;

				let FILE_DOWNLOAD;

				fetch(URL, {
					headers: {
						'Accept-Language': this.$store.getters.language,
						'Authorization': getToken(),
						'accept': 'application/json',
					},
				}).then(async(res) => {
					let filename = decodeURI(res.headers.get('content-disposition').split(`filename*=utf-8''`)[1]) || `実務実績月別_{${YEAR_MONTH}}`;
					filename = filename.replaceAll('"', '');
					await res.blob().then((res) => {
						FILE_DOWNLOAD = res;
					});
					const fileURL = window.URL.createObjectURL(FILE_DOWNLOAD);
					const fileLink = document.createElement('a');

					fileLink.href = fileURL;
					fileLink.setAttribute('download', filename);
					document.body.appendChild(fileLink);

					fileLink.click();
				})
					.catch((err) => {
						console.log(err);
					});
			}
			if (this.selectTable === CONSTANT.LIST_SHIFT.SALARY_TABLE) {
				const sort = {
					field: this.sortTable.salaryTable.sortBy,
					sortby: this.sortTable.salaryTable.sortType,
				};
				// console.log('sort table: ', this.sortTable);

				let SORT = '';

				if (sort.field === 'driver_code') {
					if (sort.sortby) {
						SORT = '&sortby_code=asc';
					} else {
						SORT = '&sortby_code=desc';
					}
				}

				if (sort.field === 'flag') {
					if (sort.sortby) {
						SORT = '&sortby_driver_type=asc';
					} else {
						SORT = '&sortby_driver_type=desc';
					}
				}

				const STATUS_VIEW = '&status_view=fix';

				const YEAR = this.pickerYearMonth.year || null;
				const MONTH = this.pickerYearMonth.month || null;

				const YEAR_MONTH = `${YEAR}-${MONTH < 10 ? `0${MONTH}` : `${MONTH}`}`;

				let VIEW_DATE = '';
				const TYPE = '&type=grade-tab';

				VIEW_DATE = `date=${YEAR_MONTH}` || null;

				const URL = `/api${CONSTANT.URL_API.GET_EXPORT_SHIFT_FILE}?${VIEW_DATE}${TYPE}${SORT}`;

				// console.log('URL DOWNLOAD ==>', URL)
				let FILE_DOWNLOAD;

				fetch(URL, {
					headers: {
						'Accept-Language': this.$store.getters.language,
						'Authorization': getToken(),
						'accept': 'application/json',
					},
				}).then(async(res) => {
					// console.log(res);
					let filename = decodeURI(res.headers.get('content-disposition').split(`filename*=utf-8''`)[1]) || `実務実績月別_{${YEAR_MONTH}}`;
					filename = filename.replaceAll('"', '');
					await res.blob().then((res) => {
						FILE_DOWNLOAD = res;
					});
					const fileURL = window.URL.createObjectURL(FILE_DOWNLOAD);
					const fileLink = document.createElement('a');
					fileLink.href = fileURL;
					fileLink.setAttribute('download', filename);
					document.body.appendChild(fileLink);

					fileLink.click();
				})
					.catch((err) => {
				    console.log(err);
					});
			}
		},

		onExportPDF() {
			if (this.selectTable === CONSTANT.LIST_SHIFT.SHIFT_TABLE) {
				const sort = {
					field: this.sortTable.shiftTable.sortBy,
					sortby: this.sortTable.shiftTable.sortType,
				};

				let SORT = '';

				if (sort.field === 'driver_code') {
					if (sort.sortby) {
						SORT = '&sortby_code=asc';
					} else {
						SORT = '&sortby_code=desc';
					}
				}

				if (sort.field === 'flag') {
					if (sort.sortby) {
						SORT = '&sortby_driver_type=asc';
					} else {
						SORT = '&sortby_driver_type=desc';
					}
				}

				let START_DATE = '';
				let END_DATE = '';

				if (this.selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK) {
					START_DATE = this.pickerWeek.listDate[0].text;
					END_DATE = this.pickerWeek.listDate[this.pickerWeek.listDate.length - 1].text;
				}

				const DATE = `start_date=${START_DATE}&end_date=${END_DATE}`;

				const NAME_START_DATE = START_DATE.replace(/-+/g, '');
				const NAME_END_DATE = END_DATE.replace(/-+/g, '');

				const NAME_DATE = `${NAME_START_DATE}-${NAME_END_DATE}`;

				const YEAR = this.pickerYearMonth.year || null;
				const MONTH = this.pickerYearMonth.month || null;

				const YEAR_MONTH = `${YEAR}-${MONTH < 10 ? `0${MONTH}` : `${MONTH}`}`;

				const KEY_DATE = `&date=${YEAR_MONTH}`;

				const URL = `/api${CONSTANT.URL_API.GET_EXPORT_SHIFT_PDF_ONLY_WEEK}?${DATE}${SORT}${KEY_DATE}`;

				let FILE_DOWNLOAD;

				fetch(URL, {
					headers: {
						'Accept-Language': this.$store.getters.language,
						'Authorization': getToken(),
						'accept': 'application/json',
					},
				}).then(async(res) => {
					let filename = `シフト表_{${NAME_DATE}}`;
					filename = filename.replaceAll('"', '');
					await res.blob().then((res) => {
						FILE_DOWNLOAD = res;
					});
					const fileURL = window.URL.createObjectURL(FILE_DOWNLOAD);
					const fileLink = document.createElement('a');

					fileLink.href = fileURL;
					fileLink.setAttribute('download', filename);
					document.body.appendChild(fileLink);

					fileLink.click();
				})
					.catch((err) => {
						console.log(err);
					});
			}

			if (this.selectTable === CONSTANT.LIST_SHIFT.PRACTICAL_ACHIEVEMENTS_MONTHLY) {
				const sort = {
					field: this.sortTable.pracitcalAchievementsTable.sortBy,
					sortby: this.sortTable.pracitcalAchievementsTable.sortType,
				};

				let SORT = '';

				if (sort.field === 'driver_code') {
					if (sort.sortby) {
						SORT = '&sortby_code=asc';
					} else {
						SORT = '&sortby_code=desc';
					}
				}

				if (sort.field === 'flag') {
					if (sort.sortby) {
						SORT = '&sortby_driver_type=asc';
					} else {
						SORT = '&sortby_driver_type=desc';
					}
				}

				const STATUS_VIEW = '&status_view=month';

				const YEAR = this.pickerYearMonth.year || null;
				const MONTH = this.pickerYearMonth.month || null;

				const YEAR_MONTH = `${YEAR}-${MONTH < 10 ? `0${MONTH}` : `${MONTH}`}`;

				let VIEW_DATE = '';

				VIEW_DATE = `view_date=${YEAR_MONTH}` || null;

				const URL = `/api${CONSTANT.URL_API.GET_EXPORT_PRACTICAL_PERFORMANCE_PDF}?${VIEW_DATE}${STATUS_VIEW}${SORT}`;

				let FILE_DOWNLOAD;

				fetch(URL, {
					headers: {
						'Accept-Language': this.$store.getters.language,
						'Authorization': getToken(),
						'accept': 'application/json',
					},
				}).then(async(res) => {
					let filename = `実務実績月別_{${YEAR_MONTH}}`;
					filename = filename.replaceAll('"', '');
					await res.blob().then((res) => {
						FILE_DOWNLOAD = res;
					});
					const fileURL = window.URL.createObjectURL(FILE_DOWNLOAD);
					const fileLink = document.createElement('a');

					fileLink.href = fileURL;
					fileLink.setAttribute('download', filename);
					document.body.appendChild(fileLink);

					fileLink.click();
				})
					.catch((err) => {
						console.log(err);
					});
			}

			if (this.selectTable === CONSTANT.LIST_SHIFT.PRACTICAL_PERFORMANCE_BY_CLOSING_DATE) {
				const sort = {
					field: this.sortTable.pracitcalPerformanceTable.sortBy,
					sortby: this.sortTable.pracitcalPerformanceTable.sortType,
				};

				let SORT = '';

				if (sort.field === 'driver_code') {
					if (sort.sortby) {
						SORT = '&sortby_code=asc';
					} else {
						SORT = '&sortby_code=desc';
					}
				}

				if (sort.field === 'flag') {
					if (sort.sortby) {
						SORT = '&sortby_driver_type=asc';
					} else {
						SORT = '&sortby_driver_type=desc';
					}
				}

				const STATUS_VIEW = '&status_view=fix';

				const YEAR = this.pickerYearMonth.year || null;
				const MONTH = this.pickerYearMonth.month || null;

				const YEAR_MONTH = `${YEAR}-${MONTH < 10 ? `0${MONTH}` : `${MONTH}`}`;

				let VIEW_DATE = '';

				VIEW_DATE = `view_date=${YEAR_MONTH}` || null;

				const URL = `/api${CONSTANT.URL_API.GET_EXPORT_PRACTICAL_PERFORMANCE_PDF}?${VIEW_DATE}${STATUS_VIEW}${SORT}`;

				let FILE_DOWNLOAD;

				fetch(URL, {
					headers: {
						'Accept-Language': this.$store.getters.language,
						'Authorization': getToken(),
						'accept': 'application/json',
					},
				}).then(async(res) => {
					let filename = `実務実績月別_{${YEAR_MONTH}}`;
					filename = filename.replaceAll('"', '');
					await res.blob().then((res) => {
						FILE_DOWNLOAD = res;
					});
					const fileURL = window.URL.createObjectURL(FILE_DOWNLOAD);
					const fileLink = document.createElement('a');

					fileLink.href = fileURL;
					fileLink.setAttribute('download', filename);
					document.body.appendChild(fileLink);

					fileLink.click();
				})
					.catch((err) => {
						console.log(err);
					});
			}
		},

		setModalLogAI(status) {
			if ([true, false].includes(status)) {
				this.showModalLogAI = status;
			} else {
				this.showModalLogAI = true;
			}
		},

		async handleClickViewLog() {
			this.isLoadingLog = true;
			this.setModalLogAI(true);

			try {
				const PARAMS = {
					status: 'list',
					page: this.paginationLogAI.current_page,
				};

				const { code, data } = await getListMessageResponseAI(CONSTANT.URL_API.GET_MESSAGE_RESPONSE_AI, PARAMS);

				if (code === 200) {
					this.listLogAI = data.result;
					this.paginationLogAI.current_page = data.pagination.current_page;
					this.paginationLogAI.total_records = data.pagination.total_records;
				}

				this.isLoadingLog = false;
			} catch (error) {
				this.isLoadingLog = false;
				console.log(error);
			}
		},

		onCloseViewLog() {
			this.setModalLogAI(false);

			this.listLogAI.length = 0;
			this.paginationLogAI = {
				current_page: 1,
				per_page: 15,
				total_records: 0,
			};
		},

		setModalDetailLogAI(status) {
			if ([true, false].includes(status)) {
				this.showModalDetailLogAI = status;
			} else {
				this.showModalDetailLogAI = true;
			}
		},

		onClickViewDetailLogAI(log) {
			const STATUS = log.item.status;

			if (['error', 'check'].includes(STATUS)) {
				this.setModalDetailLogAI(true);
				this.listDetailLogAI = log.item.message;
			}
		},

		onCloseDetailViewLog() {
			this.setModalDetailLogAI(false);
			this.listDetailLogAI = [];
		},
	},
};
</script>

<style lang="scss" scoped>
	@import "@/scss/variables";

	.list-shift {
		height: calc(100vh - 100px);

		&__header {
			.zone-left {
				display: flex;
				height: 100%;
				line-height: 48px;

				.zone-title {
					justify-content: left;
					display: flex;
					justify-content: center;
					align-items: center;

					.title-page {
						font-size: 25px;
					}
				}

				.zone-select-week-month {
					display: flex;
					justify-content: center;
					align-items: center;

					margin: 0 20px;

					.btn-select-week-month {
						background-color: $white;
						border-color: $main;
						color: $main;
						font-weight: 600;
					}

					.btn-select-week-month.active {
						background-color: $main;
						color: $white;
					}
				}

				.zone-calendar-week {
					display: flex;
					justify-content: center;
					align-items: center;
				}
			}

			.zone-right {
				display: flex;
				justify-content: flex-end;

				.item-function {
					padding: 0 20px;

					cursor: pointer;

					.show-icon {
						i {
							font-size: 25px;
							color: $dusty-gray;

							display: flex;
							align-items: center;
							justify-content: center;

							margin-bottom: 5px;
						}
					}

					.show-text {
						text-align: center;
						font-weight: bold;
						color: $dusty-gray;
						font-size: 12px;
					}

					&:hover {
						.show-icon {
							i {
								color: $di-serria;

								display: flex;
								align-items: center;
								justify-content: center;

								margin-bottom: 5px;
							}
						}

						.show-text {
							text-align: center;
							font-weight: bold;
							color: $di-serria;
							font-size: 12px;
						}
					}
				}
			}
		}

		&__control {
			margin-bottom: 10px;

			.control-button-group {
				background-color: $wild-sand;
				color: $sirocco;

				font-size: 12px;
				min-width: 120px;

				span {
					font-weight: bold;
				}
			}

			.control-button-group.active {
				background-color: $main;
				color: $white;
			}

			.btn-control {
				line-height: 50px;

				.btn-edit {
					background-color: $main;
					color: $white;
					font-weight: 600;

					border-color: transparent;

					&:hover {
						opacity: 0.8;
					}
				}
			}
			.button-text-left {
				width: 358px;
			}
		}

		&__table {
			.zone-table {
				height: calc(100vh - 240px);
				overflow: auto;

				table {
					thead {
						tr {
							th {
                                position: sticky;
                                z-index: 9;

								div {
									display: flex;
									align-items: center;
									justify-content: center;
								}

								background-color: $main;
								color: $white;
								text-align: center;
                                vertical-align: middle;

								padding: 5px 0;

                                top: 0;
							}

                            th.fix-header {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 0;
                            }

							th.th-show-date {
								padding: 5px 0;
							}

							th.th-employee-number {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 0;

								width: 150px;
							}

							th.th-type-employee {
                                position: sticky;
                                top: 37px;
                                z-index: 10;
                                left: 150px;

                                width: 180px;

                                cursor: pointer;
                            }

							th.th-full-name {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 300px;

                                width: 240px;

                                cursor: pointer;
							}
						}

                        tr:nth-child(2) {
                            position: sticky;
                            z-index: 10;
                            top: 37px;
                        }
					}

					tbody {
						tr {
							td {
								text-align: center;
								padding: 0;

								min-width: 150px;
							}

							td.td-employee-number,
							td.td-type-employee,
							td.td-full-name {
								background-color: $sub-main;

								font-weight: bold;

								vertical-align: middle;

                                padding: 5px;
							}

							td.td-type-employee {
                                position: sticky;
                                top: 0;
                                z-index: 8;
                                left: 150px;
                            }

                            td.td-employee-number {
                                position: sticky;
                                z-index: 9;
								left: 150px;
                                top: 0;
                                left: 0;
                            }

                            td.td-full-name {
                                position: sticky;
                                z-index: 9;
                                top: 0;
								left: 300px;
                            }
						}
					}
				}

                table.table-salary {
					thead {
						tr {
							th {
                                position: sticky;
                                z-index: 9;

								div {
									display: flex;
									align-items: center;
									justify-content: center;
								}

								background-color: $main;
								color: $white;
								text-align: center;
                                vertical-align: middle;

								padding: 5px 0;

                                top: 0;
							}

                            th.fix-header {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 0;
                            }

							th.th-show-date {
								padding: 5px 0;
							}

							th.th-employee-number {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 0;

								width: 150px;
							}

							th.th-type-employee {
                                position: sticky;
                                top: 37px;
                                z-index: 10;
                                left: 150px;

                                width: 180px;

                                cursor: pointer;
                            }

							th.th-full-name {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 300px;

                                width: 240px;

                                cursor: pointer;
							}

							th.th-total {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 300px;

                                width: 240px;

                                cursor: pointer;
							}
						}

                        tr:nth-child(2) {
                            position: sticky;
                            z-index: 10;
                            top: 37px;
                        }
					}

					tbody {
						tr {
							td {
								text-align: center;
								padding: 0;

								min-width: 150px;
							}

							td.td-employee-number,
							td.td-type-employee,
							td.td-full-name,
                            td.td-total {
								background-color: $sub-main;

								font-weight: bold;

								vertical-align: middle;

                                padding: 5px;
							}

							td.td-type-employee {
                                position: sticky;
                                top: 0;
                                z-index: 8;
                                left: 150px;
                            }

                            td.td-employee-number {
                                position: sticky;
                                z-index: 9;
								left: 150px;
                                top: 0;
                                left: 0;
                            }

                            td.td-full-name {
                                position: sticky;
                                z-index: 9;
                                top: 0;
								left: 300px;
                            }

                            td.td-total {
                                position: sticky;
                                z-index: 9;
                                top: 0;
								left: 0;

                                span {
                                    float: right;
                                    margin-right: 50px;
                                }
                            }
						}
					}
                }

				table.table-practical-achievements-monthly {
					thead {
						tr {
							th {
								min-width: 150px;

								vertical-align: middle;
							}

                            th.driver-code {
                                position: sticky;
                                top: 0;
                                left: 0;
                                z-index: 10;

                                cursor: pointer;

                                min-width: 150px;
                                max-width: 150px
                            }

                            th.driver-flag {
                                position: sticky;
                                top: 0;
                                left: 150px;
                                z-index: 10;

                                cursor: pointer;

                                min-width: 180px;
                                max-width: 180px
                            }

                            th.driver-name{
                                position: sticky;
                                top: 0;
                                left: 330px;
                                z-index: 10;
                            }
						}
					}

					tbody {
						tr {
							td {
								&:nth-child(1),
								&:nth-child(2),
                                &:nth-child(3) {
									background-color: $sub-main;

									font-weight: bold;
								}

								padding: 5px;
							}

                            td.driver-code {
                                position: sticky;
                                top: 0;
                                left: 0;
                                z-index: 9;
                            }

                            td.driver-flag {
                                position: sticky;
                                top: 0;
                                left: 150px;
                                z-index: 9;

                                min-width: 180px;
                                max-width: 180px;
                            }

                            td.driver-name {
                                position: sticky;
                                top: 0;
                                left: 330px;
                                z-index: 9;

                                min-width: 150px;
                                max-width: 150px
                            }

                            td.table-empty {
                                background-color: $white;
                            }
						}
					}
				}

				table.table-practical-performance-by-closing-date {
					thead {
						tr {
							th {
								min-width: 150px;

								vertical-align: middle;
							}

                            th.driver-code {
                                position: sticky;
                                top: 0;
                                left: 0;
                                z-index: 10;

                                cursor: pointer;

                                min-width: 150px;
                                max-width: 150px
                            }
                            th.driver-flag {
                                position: sticky;
                                top: 0;
                                left: 150px;
                                z-index: 10;

                                cursor: pointer;

                                min-width: 180px;
                                max-width: 180px
                            }

                            th.driver-name{
                                position: sticky;
                                top: 0;
                                left: 330px;
                                z-index: 10;

                                min-width: 150px;
                                max-width: 150px
                            }
						}
					}

					tbody {
						tr {
							td {
								&:nth-child(1),
								&:nth-child(2),
                                &:nth-child(3) {
									background-color: $sub-main;

									font-weight: bold;
								}

								padding: 5px;
							}

                            td.driver-code {
                                position: sticky;
                                top: 0;
                                left: 0;
                                z-index: 9;
                            }

                            td.driver-flag {
                                position: sticky;
                                top: 0;
                                left: 150px;
                                z-index: 9;

                                min-width: 180px;
                                max-width: 180px;
                            }

                            td.driver-name {
                                position: sticky;
                                top: 0;
                                left: 330px;
                                z-index: 9;
                            }

                            td.table-empty {
                                background-color: $white;
                            }
						}
					}
				}
			}
		}
	}

    .create-ai-shift__table {
        margin-bottom: 400px;
        text-align: center;
        height: calc(84vh - 114px);
        border: 2px solid #a2ac9e;

        .content-loading-bar {
            margin-top: 250px;
            margin-left: 200px;
            .loading-bar {
                margin-right: 170px;
            }
        }
    }

    .modal-body-confirm-ai-month {
        .body-item {
            margin-top: 50px;
            margin-bottom: 50px;
        }
    }

    .modal-body-confirm-ai-day {
        .body-item {
            margin-top: 50px;
            margin-bottom: 50px;
        }
    }

    .modal-body-confirm-atmtc {
        .body-item {
            margin-top: 50px;
            margin-bottom: 50px;
        }
    }

    .modal-body-ai {
        .body-item {
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .select-type-create-shift {
            display: flex;
            flex-direction: column;
            align-items: center;

            .title-select-type-create-shift {
                margin-bottom: 30px;
                font-weight: bold;
            }

            .item-type-create-shift {
                margin-bottom: 10px;
                min-width: 180px;
                max-width: 250px;
                border-radius: 20px;
                padding: 10px;
                background-color: $main;
                color: $white;
                cursor: pointer;
            }
        }
    }

    .btn-modal {
        min-width: 120px;
    }

    ::v-deep .modal-body-log-ai {
        table {
            th {
                text-align: center;
                vertical-align: middle;

                background-color: $main;
                color: $white;
            }

            td {
                text-align: center;
                vertical-align: middle;
            }
        }
    }

    .duck-badge {
        padding: 5px;

        background-color: $gray;
        color: $white;

        text-align: center;
        vertical-align: middle;

        border-radius: 5px;

        font-weight: bold;

        cursor: default;
    }

    .duck-badge-on {
        background-color: $wattle;
    }

    .duck-badge-error {
        background-color: $punch;

        &:hover {
            opacity: 0.8;
        }

        cursor: pointer;
    }

    .duck-badge-success {
        background-color: $main;
    }

    .duck-badge-check {
        background-color: $wattle;

        &:hover {
            opacity: 0.8;
        }

        cursor: pointer;
    }

    .text-message {
        font-weight: bold;
    }

    .text-message-on {
        color: $wattle;
    }

    .text-message-success {
        color: $black;
    }

    .text-message-error {
        color: $punch;
    }

    .text-message-check {
        color: $black;
    }

    .modal-body-detail-log-ai {
        .show-list-message {
            .text-total {
                font-weight: bold;
            }

            .message-error {
                color: $punch;
                font-weight: bold;
            }
        }
    }
</style>
