<template>
    <aside id="private-sidebar">
        <div class="sidebar-brand-card">
            <router-link :to="{name: 'landing'}" id="sidebar-logo" class="hover-transparent">
                <span class="logo-mark">{{ logoInitials }}</span>
                <span class="logo-text">
                    {{textLogoPartOne}}<abbr>{{textLogoPartTwo}}</abbr>
                </span>
            </router-link>

            <p>Recruitment control center</p>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-group" v-for="group in menuGroups" :key="group.title">
                <p class="sidebar-title">{{ group.title }}</p>

                <router-link
                    v-for="item in group.items"
                    :key="item.routeName"
                    :to="{name: item.routeName}"
                    class="sidebar-link"
                >
                    <span class="sidebar-icon">
                        <b-icon :icon="item.icon"></b-icon>
                    </span>
                    <span>{{ item.label }}</span>
                </router-link>
            </div>
        </nav>

        <div class="sidebar-summary-card">
            <span>Today</span>
            <b-skeleton v-if="todayApplications.loading" width="52px" height="32px"></b-skeleton>
            <strong v-if="!todayApplications.loading">{{todayApplications.total}}</strong>
            <p>Job applications arrived today</p>
        </div>
    </aside>
</template>

<script>
    import {mapFields} from "vuex-map-fields";
    import moment from "moment-timezone";
    import {request} from "../../util/request";

    export default {
        data() {
            return {
                textLogoPartOne: '',
                textLogoPartTwo: '',
                todayApplications: {
                    loading: false,
                    total: 0,
                },
                menuGroups: [
                    {
                        title: 'Workspace',
                        items: [
                            {routeName: 'home', label: 'Dashboard', icon: 'speedometer2'},
                            {routeName: 'job', label: 'Jobs', icon: 'briefcase'},
                            {routeName: 'jobApplication', label: 'Applications', icon: 'card-checklist'},
                            {routeName: 'interview', label: 'Interviews', icon: 'calendar2-check'},
                        ],
                    },
                    {
                        title: 'People',
                        items: [
                            {routeName: 'user', label: 'Users', icon: 'people'},
                            {routeName: 'company', label: 'Companies', icon: 'building'},
                        ],
                    },
                    {
                        title: 'Reports',
                        items: [
                            {routeName: 'report.candidateOverview', label: 'Candidates Overview', icon: 'bar-chart'},
                            {routeName: 'report.jobOverview', label: 'Jobs Overview', icon: 'graph-up'},
                        ],
                    },
                    {
                        title: 'Masters',
                        items: [
                            {routeName: 'master.experienceLevel', label: 'Experience Levels', icon: 'layers'},
                            {routeName: 'master.qualification', label: 'Qualifications', icon: 'award'},
                            {routeName: 'master.interviewStage', label: 'Interview Stages', icon: 'diagram3'},
                            {routeName: 'master.jobDepartment', label: 'Job Departments', icon: 'collection'},
                            {routeName: 'master.documentType', label: 'Document Type', icon: 'file-earmark-text'},
                            {routeName: 'master.setting', label: 'System Setting', icon: 'gear'},
                        ],
                    },
                ],
            }
        },
        mounted() {
            this.textLogoPartOne = this.settings.text_logo_part_one;
            this.textLogoPartTwo = this.settings.text_logo_part_two;
            this.getTodayJobApplicationsCount();
        },
        methods: {
            getTodayJobApplicationsCount() {
                this.todayApplications.loading = true;

                request({
                    method: "get",
                    url: `/dashboard/today/job/applications/count`,
                    params: {
                        today: moment().tz(moment.tz.guess()).utc().format('YYYY-MM-DD'),
                    },
                })
                .then((response) => {
                    this.todayApplications.total = (response.data && response.data.total) ? response.data.total : 0;
                })
                .catch(() => {
                    this.todayApplications.total = 0;
                })
                .finally(() => {
                    this.todayApplications.loading = false;
                });
            },
        },
        computed: {
            ...mapFields([
                'settings'
            ]),
            logoInitials() {
                const firstLetter = this.textLogoPartOne ? this.textLogoPartOne[0] : 'S';
                const secondLetter = this.textLogoPartTwo ? this.textLogoPartTwo[0] : '1';

                return firstLetter + secondLetter;
            },
        },
    }
</script>
