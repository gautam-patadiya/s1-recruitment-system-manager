<template>
    <a-layout-sider collapsible :trigger="null" width="230" v-model="sidebarCollapsed">
        <div id="sidebar-logo">
            <router-link :to="{name: 'landing'}" class="text-white hover-transparent">
                <span>
                    {{textLogoPartOne}}<abbr>{{textLogoPartTwo}}</abbr>
                </span>
            </router-link>
        </div>
        <a-menu
            theme="dark"
            mode="inline">
            <a-menu-item key="home" class="mt-0">
                <router-link :to="{name: 'home'}">
                    <a-icon type="dashboard"/>
                    <span class="nav-text">Dashboard</span>
                </router-link>
            </a-menu-item>
            <a-menu-item key="job">
                <router-link :to="{name: 'job'}">
                    <a-icon type="plus-square"/>
                    <span class="nav-text">Jobs</span>
                </router-link>
            </a-menu-item>
            <a-menu-item key="job_application">
                <router-link :to="{name: 'jobApplication'}">
                    <a-icon type="pic-right" />
                    <span class="nav-text">Job Applications</span>
                </router-link>
            </a-menu-item>
            <a-menu-item key="company">
                <router-link :to="{name: 'company'}">
                    <a-icon type="database" />
                    <span class="nav-text">Companies</span>
                </router-link>
            </a-menu-item>
            <a-menu-item key="user">
                <router-link :to="{name: 'user'}">
                    <a-icon type="user"/>
                    <span class="nav-text">Users</span>
                </router-link>
            </a-menu-item>
            <a-menu-item key="interview">
                <router-link :to="{name: 'interview'}">
                    <a-icon type="clock-circle"/>
                    <span class="nav-text">Interviews</span>
                </router-link>
            </a-menu-item>
            <a-sub-menu key="report">
                <span slot="title">
                    <a-icon type="appstore"/>
                    <span>Reports</span>
                </span>
                <a-menu-item key="candidateOverview">
                    <router-link :to="{name: 'report.candidateOverview'}">
                        <span class="nav-text">Candidates Overview</span>
                    </router-link>
                </a-menu-item>
                <a-menu-item key="jobOverview">
                    <router-link :to="{name: 'report.jobOverview'}">
                        <span class="nav-text">Jobs Overview</span>
                    </router-link>
                </a-menu-item>
            </a-sub-menu>
            <a-sub-menu key="master">
                <span slot="title"><a-icon type="copy"/><span>Masters</span></span>
                <a-menu-item key="experienceLevel">
                    <router-link :to="{name: 'master.experienceLevel'}">
                        <span class="nav-text">Experience Levels</span>
                    </router-link>
                </a-menu-item>
                <a-menu-item key="qualification">
                    <router-link :to="{name: 'master.qualification'}">
                        <span class="nav-text">Qualifications</span>
                    </router-link>
                </a-menu-item>
                <a-menu-item key="interviewStage">
                    <router-link :to="{name: 'master.interviewStage'}">
                        <span class="nav-text">Interview Stages</span>
                    </router-link>
                </a-menu-item>
                <a-menu-item key="jobDepartment">
                    <router-link :to="{name: 'master.jobDepartment'}">
                        <span class="nav-text">Job Departments</span>
                    </router-link>
                </a-menu-item>
                <a-menu-item key="documentType">
                    <router-link :to="{name: 'master.documentType'}">
                        <span class="nav-text">Document Type</span>
                    </router-link>
                </a-menu-item>
                <a-menu-item key="setting">
                    <router-link :to="{name: 'master.setting'}">
                        <span class="nav-text">System Setting</span>
                    </router-link>
                </a-menu-item>
            </a-sub-menu>
        </a-menu>
    </a-layout-sider>
</template>
<script>
    import {mapFields} from "vuex-map-fields";

    export default {
        data() {
            return {
                collapsed: true,
                textLogoPartOne: '',
                textLogoPartTwo: '',
            }
        },
        mounted() {
            this.textLogoPartOne = this.settings.text_logo_part_one;
            this.textLogoPartTwo = this.settings.text_logo_part_two;
        },
        computed: {
            ...mapFields([
                'sidebarCollapsed',
                'settings'
            ]),
            activeKey() {
                switch (this.$router.currentRoute.name) {
                    case 'master.experienceLevel':
                    case 'master.qualification':
                    case 'master.interviewStage':
                    case 'master.jobDepartment':
                        return 'master';
                    default:
                        return this.$router.currentRoute.name;
                }
            }
        },

    }
</script>
