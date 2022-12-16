<template>
    <b-col>
        <b-container class="container">
            <div class="page-schedule">
                <div class="page-schedule__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title">
                                <span class="title-page"
                                
                                >
                                    {{ $t('LIST_SCHEDULE.TITLE_LIST_SCHEDULE') }}
                                </span>
                            </div>
                        </b-col>
                        <b-col>
                            <div class="zone-item">
                                <div class="zone-title">
                                <span class="title-edit"
                                @click="goToCreateSchedule()"
                                >
                                    {{ $t('APP.BUTTON_SIGN_UP') }}
                                </span>
                            </div>
                            </div>
                        </b-col>
                    </b-row>
                    <LineGray />
                </div>
            </div>
                
            <div class="page-schedule">
                <div class="page-schedule__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title">                                
                                <span class="title-page">
                                    {{ $t('LIST_SCHEDULE.TITLE_LIST_SCHEDULE') }}
                                </span>
                            </div>
                        </b-col>
                        <b-col>
                            <div class="zone-item">
                                <div
                                    class="item-function"
                                    @click="handleClickImport()"
                                >
                                    <div class="show-icon">
                                        <i class="fas fa-file-spreadsheet" />
                                    </div>
                                    <div class="show-text">
                                        <span>Excel取り込み</span>
                                    </div>
                                </div>
                                <div class="item-function" @click="onClickExport()">
                                    <div class="show-icon">
                                        <i class="fas fa-file-excel" />
                                    </div>
                                    <div class="show-text">
                                        Excel出力
                                    </div>
                                </div>
                            </div>
                        </b-col>
                    </b-row>
                </div>
                <div class="page-schedule__body">
                    <div class="zone-table">
                        <b-table-simple 
                            bordered
                            no-border-collapse>
                            <b-thead class="zone-table__head">
                                <b-tr>
                                    <b-th>
                                        <b-form-checkbox
                                            id="checkbox-1"
                                            v-model="status"
                                            name="checkbox-1"
                                            value="accepted"
                                            unchecked-value="not_accepted"
                                            >
                                        </b-form-checkbox>
                                    </b-th>
                                    <b-th                                        
                                        :rowspan="2"
                                    >
                                        <b-row class="row-course-id">
                                            {{ $t('LIST_COURSE.TABLE_COURSE_ID') }}
                                            <b-col class="icon-sorts">
                                                <div class="text-right">
                                                    <i
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </b-th>
                                    <b-th                                        
                                        :rowspan="2"
                                    >
                                        <b-row class="row-course-id">
                                            {{ $t('LIST_SCHEDULE.TABLE_COURSE_DATE') }}
                                            <b-col class="icon-sorts">
                                                <div class="text-right">
                                                    <i
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </b-th>
                                    <b-th                                        
                                        :rowspan="2"
                                    >
                                        <b-row class="row-course-id">
                                            {{ $t('LIST_SCHEDULE.TABLE_COURSE_NAME') }}
                                            <b-col class="icon-sorts">
                                                <div class="text-right">
                                                    <i
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </b-th>
                                    <b-th                                        
                                    >
                                        <b-row class="row-course-id">
                                            {{ $t('LIST_SCHEDULE.TABLE_CUSTUM_NAME') }}
                                            <b-col class="icon-sorts">
                                                <div class="text-right">
                                                    <i
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </b-th>
                                    <b-th                                        
                                    >
                                        <b-row class="row-course-id">
                                            {{ $t('LIST_SCHEDULE.TABLE_DEPATURE_PLACE') }}
                                            <b-col class="icon-sorts">
                                                <div class="text-right">
                                                    <i
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </b-th>
                                    <b-th                                        
                                        :rowspan="2"
                                    >
                                        <b-row class="row-course-id">
                                            {{ $t('LIST_SCHEDULE.TABLE_ARRIVAL_PLACE') }}
                                            <b-col class="icon-sorts">
                                                <div class="text-right">
                                                    <i
                                                        class="fad fa-sort-up icon-sort"
                                                    />
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </b-th>
                                    <b-th
                                        :rowspan="2"
                                        class="text-center th-control"
                                    >
                                        {{ $t('LIST_SCHEDULE.TABLE_FREIGHT_COST') }}
                                    </b-th>
                                    <b-th
                                        :rowspan="2"
                                        class="text-center th-control"
                                    >
                                        {{ $t('LIST_COURSE.TABLE_DELETE') }}
                                    </b-th>
                                </b-tr>
                            </b-thead>
                            <b-tbody>
                                <template>
                                    <b-tr>
                                        <b-td>
                                            <b-form-checkbox
                                                id="checkbox-2"
                                                v-model="status"
                                                name="checkbox-1"
                                                value="accepted"
                                                unchecked-value="not_accepted"
                                            >
                                            </b-form-checkbox>
                                        </b-td>
                                        <b-td class="text-center">
                                            2022/12/2
                                        </b-td>
                                        <b-td class="text-center">テストコース2</b-td>
                                        <b-td class="text-center">B商事</b-td>
                                        <b-td class="text-center">B商事</b-td>
                                        <b-td class="text-center">B商事</b-td>
                                        <b-td class="text-center">55.000</b-td>
                                        <b-td class="text-center td-control">
                                            <i
                                                class="fas fa-eye"
                                                @click="onClickDetail()"
                                            />
                                        </b-td>
                                        <b-td class="text-center td-control">
                                            <i
                                                class="fas fa-trash-alt"
                                                @click="onClickDelete()"
                                            />
                                        </b-td>
                                    </b-tr>
                                </template>
                            </b-tbody>
                            
                        </b-table-simple>
                    </div>
                </div>
            </div>
        </b-container>  
    </b-col>
    
</template>

<script>
import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import { Obj2Param } from '@/utils/Obj2Param';
import { getToken } from '@/utils/handleToken';
import { setLoading } from '@/utils/handleLoading';
import { cleanObject } from '@/utils/handleObject';
import { format2Digit } from '@/utils/generateTime';
import NodeSchedule from '@/components/NodeSchedule';
import { getCalendar } from '@/api/modules/calendar';
import { getListSchedule, postImportFile } from '@/api/modules/courseSchedule';
import { getNumberDate, getTextDay } from '@/utils/convertTime';
import { validateSizeFile, validateFileCSV } from '@/utils/validate';
import TOAST_SCHEDULE_SHIFT from '@/toast/modules/scheduleShift';

export default {
	name: 'ListSchedule',
	components: {
		LineGray,
		NodeSchedule,
	},

	data() {
		return {
			
	}},
    methods: {
        goToCreateSchedule() {
			this.$router.push({ name: 'ListScheduleCreate' });
		},
        onClickDetail(){
            this.$router.push({ name: 'ListScheduleDetail' });
        }
    }
	
}
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';
    .container{
        max-width: 1500px !important;
        
    }
    
    .page-schedule {
        &__header {
            .zone-title {
                .title-page {
                    font-size: 25px;
                }
            }

            .zone-item {
                display: flex;
                justify-content: flex-end;

                .item-function {
                    cursor: pointer;

                    text-align: center;

                    margin: 0 10px;

                    .show-icon {
                        i {
                            font-size: 25px;
                            color: $dusty-gray;

                            align-items: revert;
                            justify-content: space-evenly;

                            margin-bottom: 5px;
                        }
                    }

                    .show-text {
                        text-align: center;
                        font-weight: bold;
                        color: $dusty-gray;
                        font-size: 12px;
                    }
                }
                .title-edit{
                background-color: $main-header;
                font-size: 18px;
                border-radius: 30px;
                color: $white;
                padding: 6px 10px;
                &:hover {
                        opacity: 0.8;
                        cursor: pointer;
                    }
            }
            }
            
        }
        &__body{
            .zone-table{
            margin-top: 25px;
                &__head{
                    background: $main;
                    color: $white;
                    font-weight: 700;
           } 
        }
        }
    }
</style>
