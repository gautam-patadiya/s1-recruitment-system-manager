import Vue from 'vue';
import Router from 'vue-router';
import Login from "./views/public/auth/Login";
import Dashboard from "./views/private/Dashboard";
import Error_404 from "./views/public/errors/Error_404";
import {getAuthUser, hasAuthUser, removeStorage} from "./util/utils";
import ForgotPassword from "./views/public/auth/ForgotPassword";
import ResetPassword from "./views/public/auth/ResetPassword";
import User from "./views/private/User";
import ExperienceLevel from "./views/private/ExperienceLevel";
import Qualification from "./views/private/Qualification";
import InterviewStage from "./views/private/InterviewStage";
import JobDepartment from "./views/private/JobDepartment";
import Setting from "./views/private/Setting";
import Company from "./views/private/Company";
import Job from "./views/private/Job";
import Register from "./views/public/auth/Register";
import Verify from "./views/public/auth/Verify";
import Landing from "./views/front/Landing";
import AboutUs from "./views/public/other/AboutUs";
import Profile from "./views/front/Profile";
import PrivateProfile from "./views/private/PrivateProfile";
import AppliedJob from "./components/modules/AppliedJob";
import JobApplication from "./views/private/JobApplication";
import Interview from "./views/private/Interview";
import ReportCandidate from "./views/private/ReportCandidate";
import ReportJob from "./views/private/ReportJob";
import DocumentType from "./views/private/DocumentType";
const settings = require('../public/settings.json');
Vue.use(Router);

const APP_NAME = settings.app_name;

const PUBLIC_ROUTES = [
    {
        path: '/',
        name: 'landing',
        component: Landing,
        meta: {
            layout: 'front',
            title: APP_NAME + ' | Welcome',
        }
    },

    {
        path: '/login',
        name: 'login',
        meta: {
            layout: 'public',
            title: APP_NAME + ' | Login',
        },
        component: Login,
    },

    {
        path: '/about-us',
        name: 'aboutUs',
        meta: {
            layout: 'front',
            title: APP_NAME + ' | About us',
        },
        component: AboutUs,
    },

    {
        path: '/register',
        name: 'register',
        meta: {
            layout: 'public',
            title: APP_NAME + ' | Register',
        },
        component: Register,
    },

    {
        path: '/password/forgot',
        name: 'passwordForgot',
        meta: {
            layout: 'public',
            title: APP_NAME + ' | Forgot Password',
        },
        component: ForgotPassword,
    },

    {
        path: '/password/reset/:token?',
        name: 'passwordReset',
        meta: {
            layout: 'public',
            title: APP_NAME + ' | Reset Password',
        },
        component: ResetPassword,
    },

    {
        path: '/verify/:token',
        name: 'verifyEmail',
        meta: {
            layout: 'public',
            title: APP_NAME + ' | Verify Email',
        },
        component: Verify,
    },

    {
        path: '/document/uploads/create',
        name: 'uploadDocument',
    },

    {
        path: '*',
        meta: {
            layout: 'public',
            title: APP_NAME + ' | Page not found.'
        },
        component: Error_404,
    },
];

const PRIVATE_ROUTES = [
    {
        path: '/home',
        name: 'home',
        component: Dashboard,
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
            title: APP_NAME + ' | Dashboard',
        }
    },

    {
        path: '/jobs',
        name: 'job',
        component: Job,
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
            title: APP_NAME + ' | Jobs',
        }
    },

    {
        path: '/job/applications',
        name: 'jobApplication',
        component: JobApplication,
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
            title: APP_NAME + ' | Job Applications',
        }
    },

    {
        path: '/users',
        name: 'user',
        component: User,
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
            title: APP_NAME + ' | Users',
        }
    },

    {
        path: '/companies',
        name: 'company',
        component: Company,
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
            title: APP_NAME + ' | Companies',
        }
    },

    {
        path: '/experience/levels',
        name: 'master.experienceLevel',
        component: ExperienceLevel,
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
            title: APP_NAME + ' | Experience Levels',
        }
    },

    {
        path: '/qualifications',
        name: 'master.qualification',
        component: Qualification,
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
            title: APP_NAME + ' | Qualifications',
        }
    },

    {
        path: '/interview/stages',
        name: 'master.interviewStage',
        component: InterviewStage,
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
            title: APP_NAME + ' | Interview Stages',
        }
    },

    {
        path: '/job/departments',
        name: 'master.jobDepartment',
        component: JobDepartment,
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
            title: APP_NAME + ' | Job Departments',
        }
    },

    {
        path: '/document/types',
        name: 'master.documentType',
        component: DocumentType,
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
            title: APP_NAME + ' | Job Departments',
        }
    },

    {
        path: '/profile',
        name: 'profile',
        component: Profile,
        meta: {
            layout: 'front',
            requiresAuth: true,
            title: APP_NAME + ' | Profile',
        }
    },

    {
        path: '/applied/jobs',
        name: 'appliedJob',
        component: AppliedJob,
        meta: {
            layout: 'front',
            requiresAuth: true,
            title: APP_NAME + ' | Profile',
        }
    },

    {
        path: '/interviews',
        name: 'interview',
        component: Interview,
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
            title: APP_NAME + ' | Interviews',
        }
    },

    {
        path: '/p/profile',
        name: 'privateProfile',
        component: PrivateProfile,
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
            title: APP_NAME + ' | Profile',
        }
    },

    {
        path: '/settings',
        name: 'master.setting',
        component: Setting,
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
            title: APP_NAME + ' | Setting',
        }
    },

    {
        path: '/reports/candidates',
        name: 'report.candidateOverview',
        component: ReportCandidate,
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
            title: APP_NAME + ' | Candidates Overview',
        }
    },
    {
        path: '/reports/job/overview',
        name: 'report.jobOverview',
        component: ReportJob,
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
            title: APP_NAME + ' | Jobs Overview',
        }
    },

];

function routes() {
    return [...PRIVATE_ROUTES, ...PUBLIC_ROUTES];
}

const router = new Router({
    mode: 'history',
    routes: routes(),
    scrollBehavior() {
        return {x: 0, y: 0}
    },
});

router.beforeEach((to, from, next) => {
    handleTitle(to, from);
    const user = getAuthUser(false);

    if(to.matched.some(record => record.meta.requiresAuth) && !hasAuthUser()) {
        removeStorage('auth');

        next({
            name: 'login',
            // query: {
            //     redirect: to.fullPath,
            // },
        });
    } else if(to.matched.some(record => record.meta.requiresAdmin) && hasAuthUser() && user.type != 1) {
        next({
            name: 'landing'
        });
    }

    next();
});

function handleTitle(to, from) {
    const nearestWithTitle = to.matched.slice().reverse().find(r => r.meta && r.meta.title);
    const nearestWithMeta = to.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);
    const previousNearestWithMeta = from.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);

    if(nearestWithTitle) document.title = nearestWithTitle.meta.title;

    Array.from(document.querySelectorAll('[data-vue-router-controlled]')).map(el => el.parentNode.removeChild(el));

    if(!nearestWithMeta) return;

    nearestWithMeta.meta.metaTags.map(tagDef => {
        const tag = document.createElement('meta');

        Object.keys(tagDef).forEach(key => {
            tag.setAttribute(key, tagDef[key]);
        });

        tag.setAttribute('data-vue-router-controlled', '');
        return tag;
    })
        .forEach(tag => document.head.appendChild(tag));
}

export default router;
