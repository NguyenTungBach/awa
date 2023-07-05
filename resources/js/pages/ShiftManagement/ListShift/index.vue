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
                                <!-- <b-button-group>
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
                                </b-button-group> -->
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
                                <!-- <div
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
                                </div> -->
                            </template>
                            <div
                                v-if="hasRole(role) && selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH"
                                v-show="showControlTime"
                                class="item-function"
                                @click="handleShowModal()"
                            >
                                <div class="show-icon">
                                    <i class="fas fa-calendar-check" />
                                </div>
                                <div class="show-text">
                                    <span>{{ $t("LIST_SHIFT.BUTTON_SELECT_CLOSING_DATE") }}</span>
                                </div>
                            </div>
                            <!-- <div
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
                            </div> -->
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

                        <div v-if="(selectTable === CONSTANT.LIST_SHIFT.PRACTICAL_ACHIEVEMENTS_MONTHLY || selectTable === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE)" class="zone-right">
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
                                    :class="activeSelectTable(CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE)"
                                    @click="onClickSelectTable(CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE)"
                                >
                                    <span v-html="$t('LIST_SHIFT.BUTTON_HIGHT_WAY_FEE')" />
                                </b-button>

                                <b-button
                                    :class="activeSelectTable(CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE)"
                                    @click="onClickSelectTable(CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE)"
                                >
                                    <span v-html="$t('LIST_SHIFT.BUTTON_TABLE_SALES')" />
                                </b-button>

                                <b-button
                                    :class="activeSelectTable(CONSTANT.LIST_SHIFT.PAYMENT_TABLE)"
                                    @click="onClickSelectTable(CONSTANT.LIST_SHIFT.PAYMENT_TABLE)"
                                >
                                    <span v-html="$t('LIST_SHIFT.BUTTON_PAYMENT')" />
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
                        <template v-if="selectTable === CONSTANT.LIST_SHIFT.SHIFT_TABLE">
                            <b-table-simple
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
                                        <b-th class="total-shift" :rowspan="2">
                                            {{ $t('LIST_SHIFT.TABLE_TOTAL') }}
                                        </b-th>
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
                                            <b-td class="td-total-shift">
                                                {{ totalShift }}
                                            </b-td>
                                        </tr>
                                    </template>
                                </b-tbody>
                            </b-table-simple>

                        </template>
                        <!-- Table Salary -->
                        <b-table-simple
                            v-show="(selectTable === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE)"
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
                                        {{ $t("LIST_SHIFT.SALES_MONTH") }}
                                    </b-th>
                                    <b-th rowspan="2" class="th-total">
                                        {{ $t("LIST_SHIFT.SALES_CLOSING_DATE") }}
                                    </b-th>
                                    <b-th rowspan="2" class="th-total">
                                        {{ $t("LIST_SHIFT.SALES_INVOICE") }}
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
                                        25 日
                                        <!-- {{ $t(convertValueToText(optionsTypeDriver, driverSalary.flag)) }} -->
                                    </b-td>

                                    <b-td class="td-full-name text-center">
                                        {{ driverSalary.driver_name }}
                                    </b-td>

                                    <b-td v-for="salary in driverSalary.shift_list" :id="salary.date" :key="`salary-${salary.date}`">
                                        {{ salary.value }}
                                    </b-td>

                                    <b-td>
                                        {{ listTotalSalaryMonth[index].value }}
                                    </b-td>
                                    <b-td>0</b-td>
                                    <b-td><img :src="require('@/assets/images/payment.png')" alt="Logo"></b-td>
                                </b-tr>
                            </b-tbody>
                            <b-tbody>
                                <b-tr>
                                    <b-td class="td-total" colspan="3">
                                        <span>
                                            {{ $t("LIST_SHIFT.SALES_TOTAL") }}
                                        </span>
                                    </b-td>

                                    <b-td v-for="total in listTotalSalaryDay" :key="`total-${total.id}`" class="text-center">
                                        {{ total.value }}
                                    </b-td>
                                    <b-td>0</b-td>
                                    <b-td><img :src="require('@/assets/images/payment.png')" alt="Logo"></b-td>
                                </b-tr>
                            </b-tbody>
                        </b-table-simple>
                        <!-- table Hight way -->
                        <template v-if="selectTable === CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE">
                            <b-table-simple

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
                        </template>
                        <!-- Table payment -->
                        <template v-if="selectTable === CONSTANT.LIST_SHIFT.PAYMENT_TABLE">
                            <b-table-simple

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
                        </template>
                    </div>
                </b-overlay>
            </div>
        </div>
        <b-modal
            id="modal-closing-date"
            v-model="showModalClosingDate"
            body-class="modal-closing-date"
            hide-header
            hide-footer
            no-close-on-esc
            no-close-on-backdrop
            static
            @close="handleCloseModalClosingDate()"
        >
            <div class="text-center">
                <h5 class="font-weight-bolde">
                    締日を選択してください
                </h5>
            </div>
            <div class="body-item">
                <b-row>
                    <b-col cols="8" style="margin: auto;">
                        <b-input-group>
                            <b-form-select
                                id="input-closing-date"
                                v-model="closingDate"
                                :options="optionsClosingDate"
                            />
                        </b-input-group>
                    </b-col>
                </b-row>
            </div>
            <div class="text-center">
                <b-button
                    pill
                    @click="handleCloseModalClosingDate()"
                >
                    キャンセル
                </b-button>
                <b-button
                    pill
                    class="mr-2 btn-color-active-import"
                >
                    OK
                </b-button>
            </div>
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
// import NodeCourseBase from '@/components/NodeCourseBase';
import { convertValueToText } from '@/utils/handleSelect';
import { getListPractical, getListShift, getListMessageResponseAI } from '@/api/modules/shiftManagement';
import { getTextDayInWeek, getTextDay } from '@/utils/convertTime';
import { cleanObject } from '@/utils/handleObject';
import { getToken } from '@/utils/handleToken';
import { convertValueWhenNull, convertStatusToText } from '@/utils/handleListShift';
// import CalendarMultipleWeek from '@/components/CalendarMultipleWeek';
// import CalendarMonth from '@/components/CalendarMonth';
// import Notification from '@/toast/notification';
// import CalendarFreeMonth from '@/components/CalendarFreeMonth';

export default {
	name: 'ListShift',
	components: {
		LineGray,
		NodeListShift,
		CalendarWeek,
		// CalendarMultipleWeek,
		// CalendarMonth,
		// CalendarFreeMonth,
		// NodeCourseBase,
	},

	data() {
		return {
			closingDate: '',
			showModalClosingDate: false,
			optionsClosingDate: [
				{
					value: 1,
					text: '24日',
				},
				{
					value: 2,
					text: '25日',
				},
			],

			totalShift: '',

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

			},

			sortTableCourse: {
				field: '',
				type: '',
			},

			reRender: 0,

			optionsTypeDriver: CONSTANT.LIST_DRIVER.LIST_FLAG,

			typeCreateShift: null,

			selectedDayCreateShift: {
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

				case CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE:
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

					case CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE:
						setLoading(true);
						await this.handleGetTableSalary();
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

		handleCloseModalClosingDate() {
			this.showModalClosingDate = false;
		},

		handleShowModal() {
			this.showModalClosingDate = true;
		},

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

				if (this.selectTable === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE) {
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
				console.log('get listSalary', data);
				if (code === 200) {
					console.log('get listSalary', data);
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

			// let idxid = -1;
			for (i = 0; i < day; i++) {
				for (j = 0; j < len; j++) {
					total = total + data[j].shift_list[i].value;
					// idxid = j;
				}
				dataTotalDay = {
					id: i,
					value: total,
				};
				// idxid = -1;
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

					if (this.selectTable === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE) {
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

					if (this.selectTable === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE) {
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
					CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE,
					CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE,
					CONSTANT.LIST_SHIFT.PAYMENT_TABLE,
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

					if (table === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE) {
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

					if (table === CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE) {
						setLoading(true);
						await this.handleGetListCalendar();
						await this.handleGetListShift();
						setLoading(false);
					}

					if (table === CONSTANT.LIST_SHIFT.PAYMENT_TABLE) {
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

		// async onSortTableCourse(col) {
		// 	switch (col) {
		// 		case 'course_code': {
		// 			if (this.sortTableCourse.field === 'course_code') {
		// 				if (this.sortTableCourse.type) {
		// 					this.sortTableCourse.type = !this.sortTableCourse.type;
		// 				} else {
		// 					this.sortTableCourse.type = true;
		// 				}
		// 			} else {
		// 				this.sortTableCourse.field = 'course_code';
		// 				this.sortTableCourse.type = true;
		// 			}

		// 			break;
		// 		}

		// 		case 'group': {
		// 			if (this.sortTableCourse.field === 'group') {
		// 				if (this.sortTableCourse.type) {
		// 					this.sortTableCourse.type = !this.sortTableCourse.type;
		// 				} else {
		// 					this.sortTableCourse.type = true;
		// 				}
		// 			} else {
		// 				this.sortTableCourse.field = 'group';
		// 				this.sortTableCourse.type = true;
		// 			}

		// 			break;
		// 		}
		// 	}

		// 	setLoading(true);
		// 	await this.handleGetTableCourse();
		// 	setLoading(false);
		// },

		// onSortTablePractical(col, table) {
		// 	switch (col) {
		// 		case 'sortby_code':
		// 			if (this.sortTable[table].sortBy === 'sortby_code') {
		// 				if (this.sortTable[table].sortType) {
		// 					this.sortTable[table].sortType = !this.sortTable[table].sortType;
		// 				} else {
		// 					this.sortTable[table].sortType = true;
		// 				}
		// 			} else {
		// 				this.sortTable[table].sortBy = 'sortby_code';
		// 				this.sortTable[table].sortType = true;
		// 			}

		// 			break;

		// 		case 'sortby_driver_type':
		// 			if (this.sortTable[table].sortBy === 'sortby_driver_type') {
		// 				if (this.sortTable[table].sortType) {
		// 					this.sortTable[table].sortType = !this.sortTable[table].sortType;
		// 				} else {
		// 					this.sortTable[table].sortType = true;
		// 				}
		// 			} else {
		// 				this.sortTable[table].sortBy = 'sortby_driver_type';
		// 				this.sortTable[table].sortType = true;
		// 			}

		// 			break;

		// 		default:
		// 			console.log('Handle sort table faild');

		// 			break;
		// 	}
		// },

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
			if (this.selectTable === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE) {
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

				// const STATUS_VIEW = '&status_view=fix';

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
				width: 220px;
				height: 52px;
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

							th.total-shift {
								min-width: 150px;
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

							td.td-total-shift {
								min-width: 150px;
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
								vertical-align: middle;
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

	.modal-closing-date {
        .body-item {
            margin-top: 40px;
            margin-bottom: 40px;
        }

        .message-error-import-file {
            margin-bottom: 30px;
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
