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
                                    v-else-if="(selectTable === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE)"
                                    class="title-page"
                                >
                                    {{ $t("LIST_SHIFT.TABLE_SALARY") }}
                                </span>
                                <span
                                    v-else-if="(selectTable === CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE)"
                                    class="title-page"
                                >
                                    {{ $t("LIST_SHIFT.HIGHT_WAY_FEE") }}
                                </span>
                                <span
                                    v-else-if="(selectTable === CONSTANT.LIST_SHIFT.EXPENSE_LIST)"
                                    class="title-page"
                                >
                                    {{ $t("LIST_SHIFT.TITLE_EXPENSE") }}
                                </span>
                                <span
                                    v-else
                                    class="title-page"
                                >
                                    {{ $t("LIST_SHIFT.PAYMENT_TABLE") }}
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
                                <!-- <CalendarWeek
                                    v-show="selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK"
                                    @value="getselectedYMD"
                                /> -->
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
                            <!-- <template v-if="!(isLoading.show)">
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
                            </template> -->
                            <!-- <div
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
                            </div> -->
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

                        <div v-show="(selectTable === CONSTANT.LIST_SHIFT.PRACTICAL_ACHIEVEMENTS_MONTHLY || selectTable === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE)" class="zone-right">
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
                        <div v-show="(selectTable === CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE)" class="zone-right">
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
                        <div v-show="(selectTable === CONSTANT.LIST_SHIFT.PAYMENT_TABLE)" class="zone-right">
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
                        <div v-show="(selectTable === CONSTANT.LIST_SHIFT.EXPENSE_LIST)" class="zone-right">
                            <div
                                v-if="hasRole(role) && selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH"
                                v-show="showControlTime"
                                id="select-closing-date"
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
                                    :class="activeSelectTable(CONSTANT.LIST_SHIFT.EXPENSE_LIST)"
                                    @click="onClickSelectTable(CONSTANT.LIST_SHIFT.EXPENSE_LIST)"
                                >
                                    <span v-html="$t('LIST_SHIFT.BUTTON_EXPENSE')" />
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
                                :disabled="disableEditShift"
                                @click="goToPageListShiftEdit"
                            >
                                {{ $t("LIST_SHIFT.BUTTON_EDIT") }}
                            </b-button>
                            <b-button
                                v-else
                                v-show="isLoading.show"
                                pill
                                class="btn-edit btn-color-active"
                                @click="goToPageListCourseBaseEdit"
                            >
                                {{ $t("LIST_SHIFT.BUTTON_EDIT") }}
                            </b-button>
                        </div>
                        <div v-show="selectTable === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE || selectTable === CONSTANT.LIST_SHIFT.PAYMENT_TABLE" class="text-right">
                            <b-button
                                class="btn-temporary"
                                :style="`background-color: ${handleChangeBackgroundTEM()}`"
                                :disabled="disableTem"
                                @click="turnOnButtonFinal()"
                            >
                                {{ $t("LIST_SHIFT.BUTTON_TEMPORARY") }}
                            </b-button>
                            <b-button
                                class="btn-final"
                                :style="`background-color: ${handleChangeBackgroundFinal()}`"
                                :disabled="disableFinal"
                                @click="handleClosingDate()"
                            >
                                {{ $t("LIST_SHIFT.BUTTON_FINAL_CLOSING_DATE") }}
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
                        <!-- table Shift screen PC -->
                        <b-table-simple
                            v-show="selectTable === CONSTANT.LIST_SHIFT.SHIFT_TABLE && screenWidth > 900"
                            :key="`shift-table-${reRender}`"
                            class="shift"
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
                                    <!-- <b-th class="total-shift" :rowspan="2">
                                        {{ $t('LIST_SHIFT.TABLE_TOTAL') }}
                                    </b-th> -->
                                </b-tr>
                                <b-tr>
                                    <b-th
                                        class="th-employee-number"
                                        @click="onSortTable('drivers.driver_code', 'shiftTable')"
                                    >
                                        {{ $t("LIST_SHIFT.TABLE_DATE_EMPLOYEE_NUMBER") }}
                                        <i
                                            v-if="sortTable.shiftTable.sortBy === 'drivers.driver_code' && sortTable.shiftTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.shiftTable.sortBy === 'drivers.driver_code' && sortTable.shiftTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th>
                                    <b-th
                                        class="th-type-employee"
                                        @click="onSortTable('drivers.type', 'shiftTable')"
                                    >
                                        {{ $t('LIST_SHIFT.TABLE_FLAG') }}
                                        <i
                                            v-if="sortTable.shiftTable.sortBy === 'drivers.type' && sortTable.shiftTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.shiftTable.sortBy === 'drivers.type' && sortTable.shiftTable.sortType === false"
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
                                            {{ $t(convertValueToText(optionsTypeDriver, emp.type)) }}
                                        </b-td>
                                        <td class="td-full-name text-center">
                                            {{ emp.driver_name }}
                                        </td>
                                        <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH">
                                            <template v-for="(date, idxDate) in pickerYearMonth.numberDate">
                                                <template v-if="emp.dataShift">
                                                    <NodeListShift
                                                        :key="`date-${date}-${idxDate}`"
                                                        :idx-component="idxDate + 1"
                                                        :date="date"
                                                        :check-table="CONSTANT.LIST_SHIFT.SHIFT_TABLE"
                                                        :data-node="emp.dataShift.data_by_date[idxDate]"
                                                        :emp-data="emp"
                                                        :driver-code="emp.driver_code"
                                                        :driver-name="emp.driver_name"
                                                    />
                                                </template>

                                                <NodeListShift
                                                    v-else
                                                    :key="`dateNull-${date}-${idxDate}`"
                                                    :idx-component="idxDate + 1"
                                                    :check-table="CONSTANT.LIST_SHIFT.SHIFT_TABLE"
                                                    :date="date"
                                                    :data-node="emp.dataShift"
                                                    :emp-data="emp"
                                                    :driver-code="emp.driver_code"
                                                    :driver-name="emp.driver_name"
                                                />
                                            </template>
                                        </template>
                                        <!-- <b-td class="td-total-shift">
                                            {{ emp.total_money }}
                                        </b-td> -->
                                    </tr>
                                </template>
                            </b-tbody>
                        </b-table-simple>

                        <!-- table Shift screen Mobile -->
                        <b-table-simple
                            v-show="selectTable === CONSTANT.LIST_SHIFT.SHIFT_TABLE && screenWidth < 900"
                            :key="`shift-table-mobile-${reRender}`"
                            class="shift"
                            bordered
                            no-border-collapse
                        >
                            <b-thead>
                                <b-tr>
                                    <b-th
                                        :colspan="1"
                                        class="fix-header"
                                    />
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
                                    <!-- <b-th class="total-shift" :rowspan="2">
                                        {{ $t('LIST_SHIFT.TABLE_TOTAL') }}
                                    </b-th> -->
                                </b-tr>
                                <b-tr>
                                    <!-- <b-th
                                        class="th-employee-number"
                                        @click="onSortTable('drivers.driver_code', 'shiftTable')"
                                    >
                                        {{ $t("LIST_SHIFT.TABLE_DATE_EMPLOYEE_NUMBER") }}
                                        <i
                                            v-if="sortTable.shiftTable.sortBy === 'drivers.driver_code' && sortTable.shiftTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.shiftTable.sortBy === 'drivers.driver_code' && sortTable.shiftTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th> -->
                                    <b-th
                                        class="th-type-employee"
                                    >
                                        {{ $t('LIST_SHIFT.TABLE_FLAG') }}
                                    </b-th>
                                    <!-- <b-th class="th-full-name">
                                        {{ $t("LIST_SHIFT.TABLE_FULL_NAME") }}
                                    </b-th> -->
                                    <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH">
                                        <template v-for="date in pickerYearMonth.numberDate">
                                            <b-th :key="`date-${date}`" class="date-shift">
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
                                        <!-- <td class="td-employee-number">
                                            {{ emp.driver_code }}
                                        </td> -->
                                        <b-td class="td-type-employee">
                                            {{ $t(convertValueToText(optionsTypeDriver, emp.type)) }}
                                        </b-td>
                                        <!-- <td class="td-full-name text-center">
                                            {{ emp.driver_name }}
                                        </td> -->
                                        <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH">
                                            <template v-for="(date, idxDate) in pickerYearMonth.numberDate">
                                                <template v-if="emp.dataShift">
                                                    <NodeListShift
                                                        :key="`date-${date}-${idxDate}`"
                                                        :idx-component="idxDate + 1"
                                                        :date="date"
                                                        :check-table="CONSTANT.LIST_SHIFT.SHIFT_TABLE"
                                                        :data-node="emp.dataShift.data_by_date[idxDate]"
                                                        :emp-data="emp"
                                                        :driver-code="emp.driver_code"
                                                        :driver-name="emp.driver_name"
                                                    />
                                                </template>

                                                <NodeListShift
                                                    v-else
                                                    :key="`dateNull-${date}-${idxDate}`"
                                                    :idx-component="idxDate + 1"
                                                    :check-table="CONSTANT.LIST_SHIFT.SHIFT_TABLE"
                                                    :date="date"
                                                    :data-node="emp.dataShift"
                                                    :emp-data="emp"
                                                    :driver-code="emp.driver_code"
                                                    :driver-name="emp.driver_name"
                                                />
                                            </template>
                                        </template>
                                        <!-- <b-td class="td-total-shift">
                                            {{ emp.total_money }}
                                        </b-td> -->
                                    </tr>
                                </template>
                            </b-tbody>
                        </b-table-simple>

                        <!-- Table Salary -->

                        <b-table-simple
                            v-show="selectTable === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE"
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
                                    <b-th class="th-employee-number" @click="onSortTable('customers.customer_code', 'salaryTable')">
                                        {{ $t("LIST_SHIFT.TABLE_CUSTOMER_ID") }}
                                        <i
                                            v-if="sortTable.salaryTable.sortBy === 'customers.customer_code' && sortTable.salaryTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.salaryTable.sortBy === 'customers.customer_code' && sortTable.salaryTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th>

                                    <b-th class="th-type-employee" @click="onSortTable('customers.closing_date', 'salaryTable')">
                                        {{ $t("LIST_SHIFT.TABLE_DUA_DATE_CUSTOMER") }}
                                        <i
                                            v-if="sortTable.salaryTable.sortBy === 'customers.closing_date' && sortTable.salaryTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.salaryTable.sortBy === 'customers.closing_date' && sortTable.salaryTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th>

                                    <b-th class="th-full-name">
                                        {{ $t("LIST_SHIFT.TABLE_CUSTOMER_NAME") }}
                                    </b-th>

                                    <b-th v-for="date in pickerYearMonth.numberDate" :key="`date-${date}`">
                                        <span>
                                            {{ listCalendar[date - 1] || '' }}
                                        </span>
                                    </b-th>

                                </b-tr>
                            </b-thead>

                            <b-tbody v-if="listSaleAmount.length > 0">
                                <template
                                    v-for="(emp, idx) in listSaleAmount"
                                >
                                    <tr :key="`emp-no-${idx + 1}`">
                                        <td class="td-employee-number">
                                            {{ emp.customer_code }}
                                        </td>
                                        <b-td class="td-type-employee">
                                            {{ $t(convertValueToText(optionsDate, emp.closing_date)) }}
                                        </b-td>
                                        <td class="td-full-name text-center">
                                            {{ emp.customer_name }}
                                        </td>
                                        <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH">
                                            <template v-for="(date, idxDate) in pickerYearMonth.numberDate">
                                                <template v-if="emp.date_ship_fee">
                                                    <NodeListShift
                                                        :key="`date-${date}-${idxDate}`"
                                                        :idx-component="idxDate + 1"
                                                        :date="date"
                                                        :check-table="CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE"
                                                        :data-node="emp.date_ship_fee[idxDate]"
                                                        :emp-data="emp"
                                                        :driver-code="emp.customer_code"
                                                        :driver-name="emp.customer_name"
                                                    />
                                                </template>

                                                <NodeListShift
                                                    v-else
                                                    :key="`dateNull-${date}-${idxDate}`"
                                                    :idx-component="idxDate + 1"
                                                    :check-table="CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE"
                                                    :date="date"
                                                    :data-node="emp.date_ship_fee"
                                                    :emp-data="emp"
                                                    :driver-code="emp.customer_code"
                                                    :driver-name="emp.customer_name"
                                                />
                                            </template>
                                        </template>
                                        <b-td class="td-total-month total_sale_list">
                                            {{ Number(emp.total_ship_fee_by_month) }}
                                        </b-td>
                                        <b-td class="td-total-closing-date total_sale_list">
                                            {{ Number(emp.total_ship_fee_by_closing_date) }}
                                        </b-td>
                                        <b-td class="img-pdf">
                                            <img
                                                :src="require('@/assets/images/payment.png')"
                                                alt="Logo"
                                                @click="handleShowModalExportPDF(emp)"
                                            >
                                        </b-td>
                                    </tr>
                                </template>
                            </b-tbody>
                            <!-- <b-tbody>
                                    <b-tr v-for="(driverSalary, index) in listSalary" :key="driverSalary.id">
                                        <b-td class="td-employee-number">
                                            {{ driverSalary.driver_code }}
                                        </b-td>

                                        <b-td class="td-type-employee">
                                            25 日
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
                                </b-tbody> -->
                            <b-tbody>
                                <b-tr>
                                    <b-td class="td-total" colspan="3">
                                        <span>
                                            {{ $t("LIST_SHIFT.SALES_TOTAL") }}
                                        </span>
                                    </b-td>

                                    <b-td v-for="(value, idx) in listToatalSaleByDate" :key="`total-${idx}`" class="text-center total_sale_list">
                                        {{ Number(value.total_all_ship_fee_by_date) }}
                                    </b-td>
                                    <b-td class="td-total-month total_sale_list">
                                        {{ total_all_data_sale_by_month }}
                                    </b-td>
                                    <b-td class="td-total-closing-date total_sale_list">
                                        {{ total_all_sale_by_closing_date }}
                                    </b-td>
                                    <b-td class="img-pdf">
                                        <img :src="require('@/assets/images/payment.png')" alt="Logo">
                                    </b-td>
                                </b-tr>
                            </b-tbody>
                        </b-table-simple>

                        <!-- Table Expense -->

                        <b-table-simple
                            v-show="selectTable === CONSTANT.LIST_SHIFT.EXPENSE_LIST"
                            :key="`expense-${reRender}`"
                            class="table-hight-way"
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
                                        {{ $t('LIST_SHIFT.TABLE_TOTAL_EXPENSE') }}
                                    </b-th>
                                </b-tr>
                                <b-tr>
                                    <b-th
                                        class="th-employee-number"
                                        @click="onSortTable('drivers.driver_code', 'shiftTable')"
                                    >
                                        {{ $t("LIST_SHIFT.TABLE_EXPENSE_ID") }}
                                        <i
                                            v-if="sortTable.shiftTable.sortBy === 'customers.customer_code' && sortTable.shiftTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.shiftTable.sortBy === 'customers.customer_code' && sortTable.shiftTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th>
                                    <b-th
                                        class="th-type-employee"
                                        @click="onSortTable('drivers.type', 'shiftTable')"
                                    >
                                        {{ $t('LIST_SHIFT.TABLE_EXPENSE_TYPE') }}
                                        <i
                                            v-if="sortTable.shiftTable.sortBy === 'customers.closing_date' && sortTable.shiftTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.shiftTable.sortBy === 'customers.closing_date' && sortTable.shiftTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th>
                                    <b-th class="th-full-name">
                                        {{ $t("LIST_SHIFT.TABLE_EXPENSE_NAME") }}
                                    </b-th>
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
                                            {{ $t(convertValueToText(optionsTypeDriver, emp.type)) }}
                                        </b-td>
                                        <td class="td-full-name text-center">
                                            {{ emp.driver_name }}
                                        </td>
                                        <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH">
                                            <template v-for="(date, idxDate) in pickerYearMonth.numberDate">
                                                <template v-if="emp.dataShift">
                                                    <NodeListShift
                                                        :key="`date-${date}-${idxDate}`"
                                                        :idx-component="idxDate + 1"
                                                        :date="date"
                                                        :check-table="CONSTANT.LIST_SHIFT.EXPENSE_LIST"
                                                        :data-node="emp.dataShift.data_by_date[idxDate]"
                                                        :emp-data="emp"
                                                        :driver-code="emp.driver_code"
                                                        :driver-name="emp.driver_name"
                                                    />
                                                </template>

                                                <NodeListShift
                                                    v-else
                                                    :key="`dateNull-${date}-${idxDate}`"
                                                    :idx-component="idxDate + 1"
                                                    :check-table="CONSTANT.LIST_SHIFT.EXPENSE_LIST"
                                                    :date="date"
                                                    :data-node="emp.dataShift"
                                                    :emp-data="emp"
                                                    :driver-code="emp.driver_code"
                                                    :driver-name="emp.driver_name"
                                                />
                                            </template>
                                        </template>
                                        <b-td class="td-total-shift">
                                            {{ emp.total_money }}
                                        </b-td>
                                    </tr>
                                </template>
                            </b-tbody>
                        </b-table-simple>

                        <!-- table Hight way -->

                        <b-table-simple
                            v-show="selectTable === CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE"
                            :key="`hightWay-${reRender}`"
                            class="table-hight-way"
                            bordered
                            no-border-collapse
                        >
                            <b-thead>
                                <b-tr>
                                    <b-th
                                        :colspan="3"
                                        class="fix-header"
                                    />
                                    <!-- <template
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
                                        </template> -->
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
                                        {{ $t('LIST_SHIFT.TABLE_HIGHT_WAY_MONTHLY_AMOUNT') }}
                                    </b-th>
                                </b-tr>
                                <b-tr>
                                    <b-th
                                        class="th-employee-number"
                                        @click="onSortTable('customers.customer_code', 'HightWay')"
                                    >
                                        {{ $t("LIST_SHIFT.TABLE_HIGHT_WAY_FREE_CUSTOMER_ID") }}
                                        <i
                                            v-if="sortTable.shiftTable.sortBy === 'customers.customer_code' && sortTable.shiftTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.shiftTable.sortBy === 'customers.customer_code' && sortTable.shiftTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th>
                                    <b-th
                                        class="th-type-employee"
                                        @click="onSortTable('customers.closing_date', 'HightWay')"
                                    >
                                        {{ $t('LIST_SHIFT.TABLE_HIGHT_WAY_DUE_DATE') }}
                                        <i
                                            v-if="sortTable.shiftTable.sortBy === 'customers.closing_date' && sortTable.shiftTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.shiftTable.sortBy === 'customers.closing_date' && sortTable.shiftTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th>
                                    <b-th class="th-full-name">
                                        {{ $t("LIST_SHIFT.TABLE_HIGHT_WAY_CUSTOMER_NAME") }}
                                    </b-th>
                                    <!-- <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.WEEK">
                                            <template v-for="(date, idx) in pickerWeek.listDate">
                                                <b-th :key="`date-${idx}`">
                                                    <div v-if="listCalendar.length">
                                                        {{ listCalendar[idx] || '' }}
                                                    </div>
                                                </b-th>
                                            </template>
                                        </template> -->
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
                            <b-tbody v-if="listHighWay.length > 0">
                                <template
                                    v-for="(emp, idx) in listHighWay"
                                >
                                    <tr :key="`emp-no-${idx + 1}`">
                                        <td class="td-employee-number">
                                            {{ emp.customer_code }}
                                        </td>
                                        <b-td class="td-type-employee">
                                            {{ $t(convertValueToText(optionsDate, emp.closing_date)) }}
                                        </b-td>
                                        <td class="td-full-name text-center">
                                            {{ emp.customer_name }}
                                        </td>
                                        <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH">
                                            <template v-for="(date, idxDate) in pickerYearMonth.numberDate">
                                                <template v-if="emp.dataShiftExpress">
                                                    <NodeListShift
                                                        :key="`date-${date}-${idxDate}`"
                                                        :idx-component="idxDate + 1"
                                                        :date="date"
                                                        :check-table="CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE"
                                                        :data-node="emp.dataShiftExpress.data_ship_date[idxDate]"
                                                        :emp-data="emp"
                                                        :driver-code="emp.customer_code"
                                                        :driver-name="emp.customer_name"
                                                    />
                                                </template>

                                                <NodeListShift
                                                    v-else
                                                    :key="`dateNull-${date}-${idxDate}`"
                                                    :idx-component="idxDate + 1"
                                                    :check-table="CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE"
                                                    :date="date"
                                                    :emp-data="emp"
                                                    :driver-code="emp.customer_code"
                                                    :driver-name="emp.customer_name"
                                                />
                                            </template>
                                        </template>
                                        <b-td class="td-total-shift">
                                            {{ Number(emp.total_courses_expressway_fee) }}
                                        </b-td>
                                    </tr>
                                </template>
                            </b-tbody>
                        </b-table-simple>

                        <!-- Table payment -->

                        <b-table-simple
                            v-show="selectTable === CONSTANT.LIST_SHIFT.PAYMENT_TABLE"
                            :key="`PayMent-${reRender}`"
                            class="table-payment"
                            bordered
                            no-border-collapse
                        >
                            <b-thead>
                                <b-tr>
                                    <b-th
                                        :colspan="4"
                                        class="fix-header"
                                    />
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
                                        {{ $t('LIST_SHIFT.TABLE_HIGHT_WAY_MONTHLY_AMOUNT') }}
                                    </b-th>
                                </b-tr>
                                <b-tr>
                                    <b-th
                                        class="th-employee-number"
                                        @click="onSortTable('drivers.driver_code', 'payMent')"
                                    >
                                        {{ $t("LIST_SHIFT.TABLE_PAYMENT_COMPANY_ID") }}
                                        <i
                                            v-if="sortTable.shiftTable.sortBy === 'drivers.driver_code' && sortTable.shiftTable.sortType === true"
                                            class="fad fa-sort-up icon-sort"
                                        />
                                        <i
                                            v-else-if="sortTable.shiftTable.sortBy === 'drivers.driver_code' && sortTable.shiftTable.sortType === false"
                                            class="fad fa-sort-down icon-sort"
                                        />
                                        <i
                                            v-else
                                            class="fa-solid fa-sort icon-sort-default"
                                        />
                                    </b-th>
                                    <b-th
                                        class="th-type-employee"
                                    >
                                        {{ $t('LIST_SHIFT.TABLE_PAYMENT_DUE_DATE') }}
                                    </b-th>
                                    <b-th class="th-full-name">
                                        {{ $t("LIST_SHIFT.TABLE_COMPANY_NAME") }}
                                    </b-th>
                                    <b-th class="th-vehicle-number">
                                        {{ $t("LIST_SHIFT.TABLE_VEHICLE_NUMBER") }}
                                    </b-th>
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
                            <b-tbody v-if="ListPayment.length > 0">
                                <template
                                    v-for="(emp, idx) in ListPayment"
                                >
                                    <tr :key="`emp-no-${idx + 1}`">
                                        <td class="td-employee-number">
                                            {{ emp.driver_code }}
                                        </td>
                                        <b-td class="td-type-employee">
                                            {{ emp.closing_date }}
                                        </b-td>
                                        <td class="td-full-name text-center">
                                            {{ emp.driver_name }}
                                        </td>
                                        <td class="td-vehicle-name text-center">
                                            {{ emp.vehicle_number }}
                                        </td>
                                        <template v-if="selectWeekMonth === CONSTANT.LIST_SHIFT.MONTH">
                                            <template v-for="(date, idxDate) in pickerYearMonth.numberDate">
                                                <template v-if="emp.total_payable_day">
                                                    <NodeListShift
                                                        :key="`date-${date}-${idxDate}`"
                                                        :idx-component="idxDate + 1"
                                                        :date="date"
                                                        :check-table="CONSTANT.LIST_SHIFT.PAYMENT_TABLE"
                                                        :data-node="emp.total_payable_day[idxDate]"
                                                        :emp-data="emp"
                                                        :driver-code="emp.driver_code"
                                                        :driver-name="emp.driver_name"
                                                    />
                                                </template>

                                                <NodeListShift
                                                    v-else
                                                    :key="`dateNull-${date}-${idxDate}`"
                                                    :idx-component="idxDate + 1"
                                                    :check-table="CONSTANT.LIST_SHIFT.PAYMENT_TABLE"
                                                    :date="date"
                                                    :emp-data="emp"
                                                    :driver-code="emp.driver_code"
                                                    :driver-name="emp.driver_name"
                                                />
                                            </template>
                                        </template>
                                        <b-td class="td-total-shift total_payment">
                                            {{ emp.payable_this_month }}
                                        </b-td>
                                    </tr>
                                </template>
                            </b-tbody>
                            <b-tbody>
                                <b-tr>
                                    <b-td class="td-total" colspan="4">
                                        <span>
                                            {{ $t("LIST_SHIFT.SALES_TOTAL") }}
                                        </span>
                                    </b-td>
                                    <!-- <b-td class="td-total" colspan="3">
                                        <span />
                                    </b-td> -->

                                    <b-td v-for="(value, idx) in total_payment" :key="`total-${idx}`" class="text-center total_payment">
                                        {{ Number(value.pay) }}
                                    </b-td>
                                    <b-td class="td-total-month total_payment">
                                        {{ Number(total_payment_of_month) }}
                                    </b-td>
                                </b-tr>
                            </b-tbody>
                        </b-table-simple>

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
                    @click="handleChoseClosingDate()"
                >
                    OK
                </b-button>
            </div>
        </b-modal>
        <b-modal
            id="modal-tax"
            v-model="showModalExportPDF"
            body-class="modal-tax"
            hide-header
            hide-footer
            no-close-on-esc
            no-close-on-backdrop
            static
            @close="handleCloseModalExportPDF()"
        >
            <div class="text-center">
                <h5 class="font-weight-bolde">
                    請求書出力
                </h5>
            </div>
            <div class="body-item">
                <b-row>
                    <b-col cols="8" style="margin: 10px auto; display: flex;">
                        <label class="width_label total-fare-name" for="total-fare-name">
                            {{ $t('LIST_SHIFT.TOTAL_FARE_NAME') }}
                            <span>
                                :
                            </span>
                        </label>
                        <div class="width_value total-fare">
                            {{ total_fare === '' ? 0 : Number(total_fare) }}
                        </div>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col cols="8" style="margin: 10px auto; display: flex;">
                        <label class="width_label consumption-tax" for="input-consumption-tax">
                            {{ $t('LIST_SHIFT.CONSUMPTION_TAX') }}
                            <span>
                                :
                            </span>
                        </label>
                        <b-input-group class="mb-3 width_value">
                            <b-form-input
                                id="input-consumption-tax"
                                v-model="consumption_tax"
                                type="number"
                                @input="UpdateTotalTax()"
                            />

                        </b-input-group>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col cols="8" style="margin: 10px auto; display: flex;">
                        <label class="width_label total-fare" for="total-fare">
                            {{ $t('LIST_SHIFT.TOTAL') }}
                            <span>
                                :
                            </span>
                        </label>
                        <div class="width_value total">
                            {{ total }}
                        </div>
                    </b-col>
                </b-row>
            </div>
            <div class="text-center">
                <b-button
                    pill
                    @click="handleCloseModalExportPDF()"
                >
                    キャンセル
                </b-button>
                <b-button
                    pill
                    class="mr-2 btn-color-active-import"
                    @click="handleExportPDF()"
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
// import CalendarWeek from '@/components/CalendarWeek';
import { getCalendar } from '@/api/modules/calendar';
import NodeListShift from '@/components/NodeListShift';
// import NodeCourseBase from '@/components/NodeCourseBase';
import { convertValueToText } from '@/utils/handleSelect';
import { getListPractical, getListShift, getListMessageResponseAI, postClosingDate, CheckFinalClosingDate, CheckTemporory, CheckButtonTemporary } from '@/api/modules/shiftManagement';
import { getTextDayInWeek, getTextDay } from '@/utils/convertTime';
import { cleanObject } from '@/utils/handleObject';
import { getToken } from '@/utils/handleToken';
import { convertStatusToText } from '@/utils/handleListShift';
import axios from 'axios';
// import TOAST_SUCCESS_FINAL from '@/toast/modules/scheduleShift';
// import CalendarMultipleWeek from '@/components/CalendarMultipleWeek';
// import CalendarMonth from '@/components/CalendarMonth';
// import Notification from '@/toast/notification';
// import CalendarFreeMonth from '@/components/CalendarFreeMonth';

export default {
	name: 'ListShift',
	components: {
		LineGray,
		NodeListShift,
		// CalendarWeek,
		// CalendarMultipleWeek,
		// CalendarMonth,
		// CalendarFreeMonth,
		// NodeCourseBase,
	},

	data() {
		return {
			screenWidth: window.innerWidth,
			closingDate: '',
			showModalClosingDate: false,
			showModalExportPDF: false,
			// optionsClosingDate: [
			// 	{
			// 		value: '15',
			// 		text: '15日',
			// 	},
			// 	{
			// 		value: '20',
			// 		text: '20日',
			// 	},
			// 	{
			// 		value: '25',
			// 		text: '25日',
			// 	},
			// 	{
			// 		value: '28',
			// 		text: '28日',
			// 	},
			// 	{
			// 		value: '29',
			// 		text: '29日',
			// 	},
			// 	{
			// 		value: '30',
			// 		text: '30日',
			// 	},
			// 	{
			// 		value: '31',
			// 		text: '31日',
			// 	},
			// ],

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
			listHighWay: [],
			listSaleAmount: [],
			ListPayment: [],
			listTotalExtraCost: [],
			listTableCourse: [],
			listSalary: [],

			listPractical: [],
			disableTem: false,
			disableEditShift: false,
			disableFinal: true,

			sortTable: {
				shiftTable: {
					sortBy: '',
					sortType: null,
				},

				salaryTable: {
					sortBy: '',
					sortType: null,
				},

				HightWay: {
					sortBy: '',
					sortType: null,
				},

				payMent: {
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
			optionsDate: CONSTANT.LIST_DRIVER.LIST_DATE,

			typeCreateShift: null,

			selectedDayCreateShift: {
				start: null,
				end: null,
			},

			listToatalSaleByDate: [],
			total_all_sale_by_closing_date: '',
			total_all_data_sale_by_month: '',
			total_payment: '',
			total_payment_of_month: '',

			listTotalSalaryDay: [],
			listTotalSalaryMonth: [],
			getIdExportPDF: '',
			getNameExportPDF: '',
			tax: '',
			total_fare: '',
			total: '',
			consumption_tax: '',
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

		optionsClosingDate() {
			const listDate = [
				{
					value: '15',
					text: '15日',
				},
				{
					value: '20',
					text: '20日',
				},
				{
					value: '25',
					text: '25日',
				},
				{
					value: '28',
					text: '28日',
				},
				{
					value: '29',
					text: '29日',
				},
				{
					value: '30',
					text: '30日',
				},
				{
					value: '31',
					text: '31日',
				},
			];
			const month = this.pickerYearMonth.month;
			if (month === 2) {
				return listDate.filter(item => item.value !== '31' && item.value !== '30');
			} else if ([1, 3, 5, 7, 8, 10, 12].includes(month)) {
				return listDate;
			} else {
				return listDate.filter(item => item.value !== '31');
			}
		},

		// currentPageTableLogChange() {
		// 	return this.paginationLogAI.current_page;
		// },

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
					this.showControlTime = true;
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

					case CONSTANT.LIST_SHIFT.EXPENSE_LIST:
						setLoading(true);
						await this.handleGetListCalendar();
						await this.handleGetListShift();
						setLoading(false);
						break;

					case CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE:
						setLoading(true);
						await this.handleGetSaleList();
						setLoading(false);
						break;
					case CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE:
						setLoading(true);
						await this.handleGetHightWay();
						setLoading(false);
						break;
					case CONSTANT.LIST_SHIFT.PAYMENT_TABLE:
						setLoading(true);
						await this.handleGetPayment();
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

		// currentPageTableLogChange() {
		// 	if (this.showModalLogAI) {
		// 		this.handleClickViewLog();
		// 	}
		// },

		checkEventReloadTable() {
			this.initData();
		},
	},

	mounted() {
		window.addEventListener('resize', this.handleResize);
	},

	beforeDestroy() {
		window.removeEventListener('resize', this.handleResize);
	},

	created() {
		this.initData();
	},

	methods: {
		convertValueToText,
		convertStatusToText,

		UpdateTotalTax() {
			this.total = Number(this.consumption_tax) + Number(this.total_fare);
		},

		handleResize() {
			this.screenWidth = window.innerWidth;
		},

		handleCloseModalClosingDate() {
			this.showModalClosingDate = false;
		},

		handleCloseModalExportPDF() {
			this.showModalExportPDF = false;
		},

		handleShowModal() {
			this.showModalClosingDate = true;
		},

		handleChangeBackgroundFinal() {
			if (!this.disableFinal) {
				return '#DFC900';
			} else {
				return '#B9B9B9';
			}
		},

		handleChangeBackgroundTEM() {
			if (this.disableTem) {
				return '#B9B9B9';
			} else {
				return '';
			}
		},

		async handleCheckFinalClosing() {
			try {
				setLoading(true);
				let PARAMS = {};
				const YEAR = this.pickerYearMonth.year;
				const MONTH = this.pickerYearMonth.month;

				const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

				PARAMS.month_line = YEAR_MONTH;
				PARAMS = cleanObject(PARAMS);
				const URL = CONSTANT.URL_API.GET_LIST_CASH_DISBUSEMENT;
				const response = await CheckFinalClosingDate(URL, PARAMS);
				if (response.code === 200) {
					this.disableEditShift = response.data.finalClosing;
				}
				setLoading(false);
			} catch (error) {
				setLoading(false);
				console.log(error);
			}
		},

		async handleTemmporary() {
			try {
				setLoading(true);
				let PARAMS = {};
				const YEAR = this.pickerYearMonth.year;
				const MONTH = this.pickerYearMonth.month;

				const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

				PARAMS.month_year = YEAR_MONTH;
				PARAMS = cleanObject(PARAMS);
				const URL = CONSTANT.URL_API.POST_TEMPORORY;
				const response = await CheckTemporory(URL, PARAMS);
				if (response.code === 200) {
					this.disableEditShift = response.data.finalClosing;
				}
				setLoading(false);
			} catch (error) {
				setLoading(false);
				console.log(error);
			}
		},

		// API CHECK TEMPORARY
		async handleCheckButtonTemporary() {
			try {
				setLoading(true);
				let PARAMS = {};
				const YEAR = this.pickerYearMonth.year;
				const MONTH = this.pickerYearMonth.month;

				const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

				PARAMS.month_year = YEAR_MONTH;
				PARAMS = cleanObject(PARAMS);
				const URL = CONSTANT.URL_API.GET_CHECK_BUTTON_TEMPORARY;
				const response = await CheckButtonTemporary(URL, PARAMS);
				if (response.code === 200) {
					this.disableTem = response.data.checkTemporary;
				}
				setLoading(false);
			} catch (error) {
				setLoading(false);
				console.log(error);
			}
		},

		turnOnButtonFinal() {
			this.disableFinal = false;
			this.handleTemmporary();
		},

		handleChangeToMonth(index) {
			return index < 10 ? `0${index}` : `${index}`;
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
			// await this.handleGetListShift();
			await this.onClickSelectTable();
			await this.handleCheckFinalClosing();
			await this.handleCheckButtonTemporary();
		},

		async handleGetListCalendar() {
			try {
				this.listCalendar = [];

				let START_DATE = '';
				let END_DATE = '';

				if ([CONSTANT.LIST_SHIFT.SHIFT_TABLE, CONSTANT.LIST_SHIFT.COURSE_BASE_TABLE, CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE, CONSTANT.LIST_SHIFT.EXPENSE_LIST, CONSTANT.LIST_SHIFT.PAYMENT_TABLE].includes(this.selectTable)) {
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

		handleChoseClosingDate() {
			// this.handleGetTotalExtraCost();
			this.handleGetListShift();
			this.showModalClosingDate = false;
		},

		async handleGetListShift() {
			try {
				setLoading(true);
				this.listShift.length = 0;

				let PARAMS = {};

				if (this.sortTable.shiftTable.sortBy) {
					PARAMS.field = this.sortTable.shiftTable.sortBy;
					PARAMS.sortby = this.sortTable.shiftTable.sortType ? 'desc' : 'asc';
				}
				const YEAR = this.pickerYearMonth.year;
				const MONTH = this.pickerYearMonth.month;

				const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

				PARAMS.month_year = YEAR_MONTH;
				PARAMS.closing_date = this.closingDate;

				PARAMS = cleanObject(PARAMS);

				console.log('param', PARAMS);

				const { code, data } = await getListShift(CONSTANT.URL_API.GET_LIST_SHIFT_TABLE, PARAMS);

				if (code === 200) {
					this.listShift = data;
					console.log('data', this.listShift);
					this.reloadTable();
				}
				setLoading(false);
			} catch (error) {
				console.log(error);
			}
		},

		async handleGetHightWay() {
			try {
				setLoading(true);
				this.listHighWay.length = 0;

				let PARAMS = {};

				if (this.sortTable.HightWay.sortBy) {
					PARAMS.field = this.sortTable.HightWay.sortBy;
					PARAMS.sortby = this.sortTable.HightWay.sortType ? 'desc' : 'asc';
				}
				const YEAR = this.pickerYearMonth.year;
				const MONTH = this.pickerYearMonth.month;

				const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

				PARAMS.month_year = YEAR_MONTH;

				PARAMS = cleanObject(PARAMS);

				console.log('param', PARAMS);

				const { code, data } = await getListShift(CONSTANT.URL_API.GET_LIST_HIGHT_WAY, PARAMS);

				if (code === 200) {
					this.listHighWay = data;
					this.reloadTable();
				}
				setLoading(false);
			} catch (error) {
				setLoading(false);
				console.log(error);
			}
		},

		async handleGetSaleList() {
			try {
				setLoading(true);
				this.listSaleAmount.length = 0;

				let PARAMS = {};

				if (this.sortTable.salaryTable.sortBy) {
					PARAMS.field = this.sortTable.salaryTable.sortBy;
					PARAMS.sortby = this.sortTable.salaryTable.sortType ? 'desc' : 'asc';
				}
				const YEAR = this.pickerYearMonth.year;
				const MONTH = this.pickerYearMonth.month;

				const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

				PARAMS.month_year = YEAR_MONTH;

				PARAMS = cleanObject(PARAMS);

				const { code, data } = await getListShift(CONSTANT.URL_API.GET_SALE_LIST, PARAMS);

				if (code === 200) {
					this.listSaleAmount = data.data;
					this.listToatalSaleByDate = data.total_all_sales_by_date;
					this.total_all_sale_by_closing_date = Number(data.total_all_data_sales_by_closing_date);
					this.total_all_data_sale_by_month = Number(data.total_all_data_sales_by_month);
					this.reloadTable();
				}
				setLoading(false);
			} catch (error) {
				setLoading(false);
				console.log(error);
			}
		},

		async handleGetPayment() {
			try {
				setLoading(true);
				this.ListPayment.length = 0;

				let PARAMS = {};

				if (this.sortTable.payMent.sortBy) {
					PARAMS.order_by = this.sortTable.payMent.sortBy;
					PARAMS.sort_by = this.sortTable.payMent.sortType ? 'desc' : 'asc';
				}
				const YEAR = this.pickerYearMonth.year;
				const MONTH = this.pickerYearMonth.month;

				const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

				PARAMS.month_year = YEAR_MONTH;

				PARAMS = cleanObject(PARAMS);

				const { code, data } = await getListShift(CONSTANT.URL_API.GET_LIST_PAYMENT, PARAMS);

				if (code === 200) {
					this.ListPayment = data.list_data;
					this.total_payment = data.sum_total_day;
					this.total_payment_of_month = data.sum_total_month;
					this.reloadTable();
				}
				setLoading(false);
			} catch (error) {
				setLoading(false);
				console.log(error);
			}
		},

		// API CLOSING DATE

		async handleClosingDate() {
			try {
				setLoading(true);
				const PARAMS = {};
				const YEAR = this.pickerYearMonth.year;
				const MONTH = this.pickerYearMonth.month;

				const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

				PARAMS.month_year = YEAR_MONTH;
				const URL = CONSTANT.URL_API.POST_CLOSING_DATE;
				const data = await postClosingDate(URL, PARAMS);
				if (data.code === 200) {
					// TOAST_SUCCESS_FINAL.closingDate(data.message);
					this.disableTem = true;
					this.disableFinal = true;
				}
				setLoading(false);
			} catch (error) {
				setLoading(false);
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
				case 'drivers.driver_code':
					if (this.sortTable[table].sortBy === 'drivers.driver_code') {
						if (this.sortTable[table].sortType) {
							this.sortTable[table].sortType = !this.sortTable[table].sortType;
						} else {
							this.sortTable[table].sortType = true;
						}
					} else {
						this.sortTable[table].sortBy = 'drivers.driver_code';
						this.sortTable[table].sortType = true;
					}

					setLoading(true);
					if (this.selectTable === CONSTANT.LIST_SHIFT.SHIFT_TABLE || this.selectTable === CONSTANT.LIST_SHIFT.EXPENSE_LIST) {
						await this.handleGetListShift();
					}

					if (this.selectTable === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE) {
						await this.handleGetTableSalary();
					}

					if (this.selectTable === CONSTANT.LIST_SHIFT.PAYMENT_TABLE) {
						await this.handleGetPayment();
					}
					setLoading(false);

					break;

				case 'drivers.type':
					if (this.sortTable[table].sortBy === 'drivers.type') {
						if (this.sortTable[table].sortType) {
							this.sortTable[table].sortType = !this.sortTable[table].sortType;
						} else {
							this.sortTable[table].sortType = true;
						}
					} else {
						this.sortTable[table].sortBy = 'drivers.type';
						this.sortTable[table].sortType = true;
					}

					setLoading(true);
					if (this.selectTable === CONSTANT.LIST_SHIFT.SHIFT_TABLE || this.selectTable === CONSTANT.LIST_SHIFT.EXPENSE_LIST) {
						await this.handleGetListShift();
					}

					if (this.selectTable === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE) {
						await this.handleGetTableSalary();
					}
					setLoading(false);

					break;
				case 'customers.customer_code':
					if (this.sortTable[table].sortBy === 'customers.customer_code') {
						if (this.sortTable[table].sortType) {
							this.sortTable[table].sortType = !this.sortTable[table].sortType;
						} else {
							this.sortTable[table].sortType = true;
						}
					} else {
						this.sortTable[table].sortBy = 'customers.customer_code';
						this.sortTable[table].sortType = true;
					}

					setLoading(true);
					if (this.selectTable === CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE) {
						await this.handleGetHightWay();
					}

					if (this.selectTable === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE) {
						await this.handleGetSaleList();
					}
					setLoading(false);

					break;
				case 'customers.closing_date':
					if (this.sortTable[table].sortBy === 'customers.closing_date') {
						if (this.sortTable[table].sortType) {
							this.sortTable[table].sortType = !this.sortTable[table].sortType;
						} else {
							this.sortTable[table].sortType = true;
						}
					} else {
						this.sortTable[table].sortBy = 'customers.closing_date';
						this.sortTable[table].sortType = true;
					}

					setLoading(true);
					if (this.selectTable === CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE) {
						await this.handleGetHightWay();
					}

					if (this.selectTable === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE) {
						await this.handleGetSaleList();
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
					CONSTANT.LIST_SHIFT.EXPENSE_LIST,
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
						await this.handleGetSaleList();
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
						await this.handleGetHightWay();
						setLoading(false);
					}

					if (table === CONSTANT.LIST_SHIFT.PAYMENT_TABLE) {
						setLoading(true);
						await this.handleGetListCalendar();
						await this.handleGetPayment();
						setLoading(false);
					}
					if (table === CONSTANT.LIST_SHIFT.EXPENSE_LIST) {
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

		async onExportExcel() {
			if (this.selectTable === CONSTANT.LIST_SHIFT.SHIFT_TABLE) {
				try {
					let params = {};

					if (this.sortTable.shiftTable.sortBy) {
						params.field = this.sortTable.shiftTable.sortBy;
						params.sortby = this.sortTable.shiftTable.sortType ? 'desc' : 'asc';
					}
					const YEAR = this.pickerYearMonth.year;
					const MONTH = this.pickerYearMonth.month;

					const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

					params.month_year = YEAR_MONTH;
					params.closing_date = this.closingDate;

					params = cleanObject(params);
					const URL = `/api${CONSTANT.URL_API.GET_EXPORT_SHIFT_EXCEL_ONLY_WEEK}`;
					await axios.get(URL, {
						params: params,
						responseType: 'blob',
						headers: {
							'Accept-Language': this.$store.getters.language,
							'Authorization': getToken(),
							'accept': 'application/json',
						},
					}).then((response) => {
						const url = window.URL.createObjectURL(new Blob([response.data]));
						const link = document.createElement('a');
						link.href = url;
						link.setAttribute('download', `シフト表_${YEAR_MONTH}.xlsx`);
						document.body.appendChild(link);
						link.click();
					}).catch((error) => {
						console.log(error);
					});
				} catch (error) {
					console.log(error);
				}
			}

			if (this.selectTable === CONSTANT.LIST_SHIFT.HIGHT_WAY_FEE) {
				try {
					let params = {};

					if (this.sortTable.shiftTable.sortBy) {
						params.field = this.sortTable.shiftTable.sortBy;
						params.sortby = this.sortTable.shiftTable.sortType ? 'desc' : 'asc';
					}
					const YEAR = this.pickerYearMonth.year;
					const MONTH = this.pickerYearMonth.month;

					const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

					params.month_year = YEAR_MONTH;
					params = cleanObject(params);
					const URL = `/api${CONSTANT.URL_API.GET_EXPORT_HIGHT_WAY}`;
					await axios.get(URL, {
						params: params,
						responseType: 'blob',
						headers: {
							'Accept-Language': this.$store.getters.language,
							'Authorization': getToken(),
							'accept': 'application/json',
						},
					}).then((response) => {
						const url = window.URL.createObjectURL(new Blob([response.data]));
						const link = document.createElement('a');
						link.href = url;
						link.setAttribute('download', `高速代金表_${YEAR_MONTH}.xlsx`);
						document.body.appendChild(link);
						link.click();
					}).catch((error) => {
						console.log(error);
					});
				} catch (error) {
					console.log(error);
				}
			}

			if (this.selectTable === CONSTANT.LIST_SHIFT.SALES_AMOUNT_TABLE) {
				try {
					let params = {};

					if (this.sortTable.salaryTable.sortBy) {
						params.field = this.sortTable.salaryTable.sortBy;
						params.sortby = this.sortTable.salaryTable.sortType ? 'desc' : 'asc';
					}
					const YEAR = this.pickerYearMonth.year;
					const MONTH = this.pickerYearMonth.month;

					const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

					params.month_year = YEAR_MONTH;
					params = cleanObject(params);
					const URL = `/api${CONSTANT.URL_API.GET_EXPORT_SALE_LIST}`;
					await axios.get(URL, {
						params: params,
						responseType: 'blob',
						headers: {
							'Accept-Language': this.$store.getters.language,
							'Authorization': getToken(),
							'accept': 'application/json',
						},
					}).then((response) => {
						const url = window.URL.createObjectURL(new Blob([response.data]));
						const link = document.createElement('a');
						link.href = url;
						link.setAttribute('download', `売上金額表_${YEAR_MONTH}.xlsx`);
						document.body.appendChild(link);
						link.click();
					}).catch((error) => {
						console.log(error);
					});
				} catch (error) {
					console.log(error);
				}
			}

			if (this.selectTable === CONSTANT.LIST_SHIFT.PAYMENT_TABLE) {
				try {
					let params = {};

					if (this.sortTable.salaryTable.sortBy) {
						params.order_by = this.sortTable.salaryTable.sortBy;
						params.sort_by = this.sortTable.salaryTable.sortType ? 'desc' : 'asc';
					}
					const YEAR = this.pickerYearMonth.year;
					const MONTH = this.pickerYearMonth.month;

					const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

					params.month_year = YEAR_MONTH;
					params = cleanObject(params);
					const URL = `/api${CONSTANT.URL_API.GET_EXPORT_PAYMENT}`;
					await axios.get(URL, {
						params: params,
						responseType: 'blob',
						headers: {
							'Accept-Language': this.$store.getters.language,
							'Authorization': getToken(),
							'accept': 'application/json',
						},
					}).then((response) => {
						const url = window.URL.createObjectURL(new Blob([response.data]));
						const link = document.createElement('a');
						link.href = url;
						link.setAttribute('download', `支払代金表_${YEAR_MONTH}.xlsx`);
						document.body.appendChild(link);
						link.click();
					}).catch((error) => {
						console.log(error);
					});
				} catch (error) {
					console.log(error);
				}
			}

			if (this.selectTable === CONSTANT.LIST_SHIFT.EXPENSE_LIST) {
				try {
					let params = {};

					if (this.sortTable.salaryTable.sortBy) {
						params.field = this.sortTable.salaryTable.sortBy;
						params.sortby = this.sortTable.salaryTable.sortType ? 'desc' : 'asc';
					}
					const YEAR = this.pickerYearMonth.year;
					const MONTH = this.pickerYearMonth.month;

					const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

					params.month_year = YEAR_MONTH;
					params.closing_date = this.closingDate;
					params = cleanObject(params);
					const URL = `/api${CONSTANT.URL_API.GET_EXPORT_EXPENSE}`;
					await axios.get(URL, {
						params: params,
						responseType: 'blob',
						headers: {
							'Accept-Language': this.$store.getters.language,
							'Authorization': getToken(),
							'accept': 'application/json',
						},
					}).then((response) => {
						const url = window.URL.createObjectURL(new Blob([response.data]));
						const link = document.createElement('a');
						link.href = url;
						link.setAttribute('download', `経費表_${YEAR_MONTH}.xlsx`);
						document.body.appendChild(link);
						link.click();
					}).catch((error) => {
						console.log(error);
					});
				} catch (error) {
					console.log(error);
				}
			}
		},

		handleShowModalExportPDF(data) {
			this.showModalExportPDF = true;
			this.total_fare = data.total_ship_fee_by_closing_date;
			this.consumption_tax = (Number(data.total_ship_fee_by_closing_date) * 10) / 100;
			this.total = this.consumption_tax + Number(data.total_ship_fee_by_closing_date);
			this.getIdExportPDF = data.customer_id;
			this.getNameExportPDF = data.customer_name;
			// this.handleExportPDF(data.customer_id);
		},

		async handleExportPDF() {
			const YEAR = this.pickerYearMonth.year;
			const MONTH = this.pickerYearMonth.month;

			const YEAR_MONTH = `${YEAR}-${format2Digit(MONTH)}`;

			const URL = `/api${CONSTANT.URL_API.GET_EXPORT_SALE_LIST_PDF}/${this.getIdExportPDF}?month_year=${YEAR_MONTH}&tax=${this.consumption_tax}`;
			console.log('url', URL);
			let FILE_DOWNLOAD;

			fetch(URL, {
				headers: {
					'Accept-Language': this.$store.getters.language,
					'Authorization': getToken(),
					'accept': 'application/json',
				},
			}).then(async(res) => {
				let filename = `【請求書】${this.getNameExportPDF}様_${YEAR_MONTH}`;
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
			this.showModalExportPDF = false;
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
						font-size: 22px;
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
				span {
					font-size: 11px;
				}
			}
			.btn-temporary {
				padding: 10px;
				background: #DFC900;
				border: none;
			}
			.btn-final {
				padding: 10px;
				background: #B9B9B9;
				border: none;
				margin: 0 5px;
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
								min-width: 100px;
								padding: 20px 0;

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
								cursor: pointer;
								min-width: 150px;
							}

							th.th-type-employee {
                                position: sticky;
                                top: 37px;
                                z-index: 10;
                                left: 150px;

                                min-width: 150px;

                                cursor: pointer;
                            }

							th.th-full-name {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 300px;

                                min-width: 200px;

                                cursor: pointer;
							}
						}

                        tr:nth-child(2) {
                            position: sticky;
                            z-index: 10;
                            top: 42px;
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
								vertical-align: middle;
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

				table.shift {
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
								min-width: 100px;
								padding: 20px 0;

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

							// th.total-shift {
							// 	// min-width: 150px;
							// }

							th.th-employee-number {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 0;
								cursor: pointer;
								min-width: 150px;
							}

							th.th-type-employee {
                                position: sticky;
                                top: 37px;
                                z-index: 10;
                                left: 150px;

                                min-width: 150px;

                                cursor: pointer;
                            }

							th.th-full-name {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 300px;

                                min-width: 200px;

                                cursor: pointer;
							}
						}

                        tr:nth-child(2) {
                            position: sticky;
                            z-index: 10;
                            top: 42px;
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
								// min-width: 150px;
								vertical-align: middle;
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

								padding: 20px 0;

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

                                width: 150px;

                                cursor: pointer;
                            }

							th.th-full-name {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 300px;

                                width: 200px;

                                cursor: pointer;
							}

							th.th-total {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 330px;

                                width: 240px;

                                cursor: pointer;
							}
						}

                        tr:nth-child(2) {
                            position: sticky;
                            z-index: 10;
                            top: 42px;
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

							td.img-pdf {
								cursor: pointer;
							}
							td.total_sale_list {
								font-size: 16px;
							}

                            td.td-total {
                                position: sticky;
                                z-index: 9;
                                top: 0;
								left: 0;
								padding: 25px 50px;
                                span {
                                    float: right;
                                    margin-right: 50px;
                                }
                            }
						}
					}
                }

				table.table-hight-way {
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
								min-width: 100px;
								padding: 20px 0;

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
								cursor: pointer;
								min-width: 150px;
							}

							th.th-type-employee {
                                position: sticky;
                                top: 37px;
                                z-index: 10;
                                left: 150px;

                                min-width: 150px;

                                cursor: pointer;
                            }

							th.th-full-name {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 300px;

                                min-width: 200px;

                                cursor: pointer;
							}
						}

                        tr:nth-child(2) {
                            position: sticky;
                            z-index: 10;
                            top: 42px;
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
								vertical-align: middle;
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

				table.table-payment {
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

								padding: 20px 0;

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

                                width: 150px;

                                cursor: pointer;
                            }

							th.th-vehicle-number {
								position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 500px;

                                width: 150px;

                                cursor: pointer;
							}

							th.th-full-name {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 300px;

                                width: 200px;

                                cursor: pointer;
							}

							th.th-total {
                                position: sticky;
                                z-index: 10;
                                top: 0;
                                left: 330px;

                                width: 240px;

                                cursor: pointer;
							}
						}

                        tr:nth-child(2) {
                            position: sticky;
                            z-index: 10;
                            top: 42px;
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
							td.td-vehicle-name,
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

							td.td-vehicle-name {
                                position: sticky;
                                z-index: 9;
                                top: 0;
								left: 500px;
                            }

							td.img-pdf {
								cursor: pointer;
							}

							td.total_payment {
								font-size: 16px;
							}

                            td.td-total {
                                position: sticky;
                                z-index: 9;
                                top: 0;
								left: 0;
								padding: 25px 50px;
                                span {
                                    float: right;
                                    margin-right: 50px;
                                }
                            }
						}
					}
                }
			}
		}
	}
	@media (max-width: 900px) {
		.list-shift {

			&__header {

				.zone-right {
					display: none;
				}
			}

			&__control {
				display: none;
			}

			&__table {
				.zone-table {
					table.shift {
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
									min-width: 60px;
									padding: 20px 0;

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
									min-width: 70px !important;
								}

								th.th-type-employee {
									position: sticky;
									top: 37px;
									z-index: 10;
									left: 0;

									min-width: 80px;

									cursor: pointer;
								}

							}

							tr:nth-child(2) {
								position: sticky;
								z-index: 10;
								top: 42px;
							}
						}

						tbody {
							tr {
								td {
									text-align: center;
									padding: 0;

									min-width: 0;
								}

								td.td-employee-number,
								td.td-type-employee,
								td.td-full-name {
									background-color: $sub-main;

									font-weight: bold;

									vertical-align: middle;

									// padding: 5px;
								}

								td.td-type-employee {
									position: sticky;
									top: 0;
									z-index: 8;
									left: 0;
								}

								td.td-total-shift {
									// min-width: 150px;
									vertical-align: middle;
								}
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
	.modal-tax {
		.body-item {
			margin-top: 40px;
            margin-bottom: 40px;

			.total-fare-name {
				margin-right: 10px;
			}
			.consumption-tax {
				margin-right: 10px;
			}
			.total-fare {
				margin-right: 10px;
			}
			.width_label {
				width: 30%;
			}
			.width_value {
				width: 70%;
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
