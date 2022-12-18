<template>
    <b-col>
        <b-container class="container">
            <div class="page-schedule">
                <div class="page-schedule__header">
                    <b-row>
                        <b-col>
                            <div class="zone-title">
                                <span class="title-page">
                                    {{ $t('DETAIL_SCHEDULE.TITLE_DETAIL_SCHEDULE') }}
                                </span>
                            </div>
                        </b-col>
                    </b-row>
                </div>

                <LineGray />

                <div
                    class="zone-control text-right"
                >
                    <b-button
                        pill
                        class="btn-return"
                        @click="onClickReturn()"
                    >
                        {{ $t('APP.BUTTON_RETURN') }}
                    </b-button>
                    <b-button
                        pill
                        class="btn-save"
                        @click="onClickEdit()"
                    >
                        {{ $t('APP.BUTTON_EDIT') }}
                    </b-button>
                </div>
                <div class="body-form">
                    <b-row>
                        <b-col
                            :cols="12"
                            :sm="12"
                            :md="12"
                            :lg="4"
                            :xl="4"
                        >
                            <div class="zone-avatar">
                                <img
                                    :src="require('@/assets/images/course_icon.png')"
                                    alt="Avatar schedule"
                                >
                            </div>
                        </b-col>
                        <b-col
                            :cols="12"
                            :sm="12"
                            :md="12"
                            :lg="8"
                            :xl="8"
                        >
                            <TitlePathForm>
                                {{ $t('DETAIL_SCHEDULE.BASIC_INFORMATION') }}
                            </TitlePathForm>
                            <div class="item-form">
                                <b-row>
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.SHIP_DATE')"
                                           
                                        />
                                    </b-col>
                                </b-row>
                            </div>
                            <div class="item-form">
                                <b-row>
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.COURSE_NAME')"
                                        />
                                    </b-col>
                                </b-row>
                            </div>
                            <div>
                                <b-row>
                                    <b-col
                                        :cols="12"
                                        :sm="12"
                                        :md="12"
                                        :lg="6"
                                        :xl="6"
                                        class="item-form"
                                    >
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.START_TIME')"
                                            :value="123"
                                        />
                                    </b-col>
                                    <b-col
                                        :cols="12"
                                        :sm="12"
                                        :md="12"
                                        :lg="6"
                                        :xl="6"
                                        class="item-form"
                                    >
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.END_TIME')"
                                        
                                        />
                                    </b-col>
                                    <b-col
                                        :cols="12"
                                        :sm="12"
                                        :md="12"
                                        :lg="6"
                                        :xl="6"
                                        class="item-form"
                                    >
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.BREAK_TIME')"
                                        
                                        />
                                    </b-col>
                                </b-row>
                                <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.CUSTUM_NAME')"
                                        />
                                    </b-col>
                                </b-row>
                                <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.DEPATURE_PLACE')"
                                        />
                                    </b-col>
                                </b-row>
                                <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.ARRIVAL_PLACE')"
                                        />
                                    </b-col>
                                </b-row>
                                <b-row class="item-form">
                                    <b-col>
                                        <DetailForm
                                            :label="$t('DETAIL_SCHEDULE.FREIGHT_COST')"
                                        />
                                    </b-col>
                                </b-row>
                                
                            </div>

                            <TitlePathForm class="item-not">
                                {{ $t('DETAIL_SCHEDULE.NOTE') }}
                            </TitlePathForm>

                        </b-col>
                    </b-row>
                   
                </div>

            </div>
        </b-container>    
    </b-col>
</template>

<script>
import CONSTANT from '@/const';
import LineGray from '@/components/LineGray';
import TitlePathForm from '@/components/TitlePathForm';
import { setLoading } from '@/utils/handleLoading';
import { format2Digit } from '@/utils/generateTime';
import NodeSchedule from '@/components/NodeSchedule';
import { cleanObject } from '@/utils/handleObject';
import { getCalendar } from '@/api/modules/calendar';
import { getNumberDate, getTextDay } from '@/utils/convertTime';
import TOAST_SCHEDULE_MANAGEMENT from '@/toast/modules/scheduleManagement';
import { getListSchedule, postImportFile, postListSchedule } from '@/api/modules/courseSchedule';
import { validateSizeFile, validateFileCSV } from '@/utils/validate';
import TOAST_SCHEDULE_SHIFT from '@/toast/modules/scheduleShift';
import DetailForm from '@/components/DetailForm';
export default {
	name: 'ListSchedule',
	components: {
		LineGray,
		NodeSchedule,
        TitlePathForm,
        DetailForm,
	},

	data() {
		return {}
	},

	computed: {
		
	},

	watch: {
			
	},

	created() {
		
	},

	methods: {
        onClickReturn() {
			this.$router.push({ name: 'ListSchedule' });
		},
        onClickEdit(){
            this.$router.push({ name: 'ListScheduleEdit' });
        }
    }
	
};
</script>

<style lang="scss" scoped>
    @import '@/scss/variables';

    .page-schedule {
            .zone-control {
                margin-bottom: 10px;

                .btn-return,
                .btn-save {
                    &:hover {
                        opacity: 0.8;
                    }

                    border-color: transparent;
                }
                .btn-return{
                    background-color: $gray;
                }
                .btn-save {
                    background-color: $main;
                    color: $white;
                    font-weight: 600;
                }
            }
            .body-form {
                border: 1px solid $geyser;
                margin-top: 15px;
                padding: 20px;
                .zone-avatar {
                    height: 100%;

                    display: flex;
                    justify-content: center;
                    align-items: center;
                    vertical-align: middle;

                    img {
                        height: 270px;
                    }
                }
                .item-form{
                    margin-top: 20px;
                }
                .item-not{
                    margin: 40px 0;
                }
            }
    }
</style>
