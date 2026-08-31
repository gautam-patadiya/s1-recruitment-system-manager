import toastBus from './toastBus';

function showToast(variant, title, message = '') {
    toastBus.$emit('show-toast', {
        title: title,
        message: message,
        variant: variant,
    });
}

export const loginSuccess = () => showToast('success', 'Welcome! Login Success');
export const loginFailed = () => showToast('danger', 'Login failed! Make sure email verified.');
export const registerSuccess = () => showToast('success', 'Congratulations! Register Success.', 'Verify Email! So can login.');
export const registerFailed = () => showToast('danger', 'Registration failed!');

export const sessionExpires = () => showToast('danger', 'Session expired!');
export const serverError = () => showToast('danger', 'Whoops! Server Error!', 'Please refresh the page and try again!');
export const forgotPasswordSuccess = () => showToast('info', 'Check inbox! We have sent you email.');
export const resetPasswordSuccess = () => showToast('info', 'Congratulations! Reset password successful.');

export const userCreated = () => showToast('success', 'User Added!');
export const userUpdated = () => showToast('info', 'User Updated!');
export const userDeleted = () => showToast('warning', 'User Deleted!');

export const experienceLevelCreated = () => showToast('success', 'Experience Level Added!');
export const experienceLevelUpdated = () => showToast('info', 'Experience Level Updated!');
export const experienceLevelDeleted = () => showToast('warning', 'Experience Level Deleted!');

export const qualificationCreated = () => showToast('success', 'Qualification Added!');
export const qualificationUpdated = () => showToast('info', 'Qualification Updated!');
export const qualificationDeleted = () => showToast('warning', 'Qualification Deleted!');

export const interviewStageCreated = () => showToast('success', 'Interview Stage Added!');
export const interviewStageUpdated = () => showToast('info', 'Interview Stage Updated!');
export const interviewStageDeleted = () => showToast('warning', 'Interview Stage Deleted!');

export const jobDepartmentCreated = () => showToast('success', 'Job Department Added!');
export const jobDepartmentUpdated = () => showToast('info', 'Job Department Updated!');
export const jobDepartmentDeleted = () => showToast('warning', 'Job Department Deleted!');

export const verifyEmailSuccess = () => showToast('info', 'Congratulations! Email Verification Success');
export const verifyEmailFailed = () => showToast('danger', 'Whoops! Looks like token mismatch.');

export const jobApplySuccess = () => showToast('info', 'Congratulations! You have applied for a job.');
export const jobApplyFailed = () => showToast('danger', 'Whoops! Looks like fields mismatch.');
export const jobApplyAlready = () => showToast('info', 'Whoops! Looks like You already applied.');

export const profileUpdated = () => showToast('info', 'Profile update successful.');

export const jobCreated = () => showToast('success', 'Job Added!');
export const jobUpdated = () => showToast('info', 'Job Updated!');
export const jobDeleted = () => showToast('warning', 'Job Deleted!');
export const jobArchived = () => showToast('warning', 'Job Archived!');

export const jobApplicationCreated = () => showToast('success', 'Job Application Added!');
export const jobApplicationUpdated = () => showToast('info', 'Job Application Updated!');

export const interviewCreated = () => showToast('success', 'Interview Added!');
export const interviewUpdated = () => showToast('info', 'Interview Updated!');
export const interviewDeleted = () => showToast('warning', 'Interview Deleted!');

export const documentTypeCreated = () => showToast('success', 'Document Type Added!');
export const documentTypeUpdated = () => showToast('info', 'Document Type Updated!');
export const documentTypeDeleted = () => showToast('warning', 'Document Type Deleted!');

export const moreThenOneUploadNotAllowed = () => showToast('warning', 'Multiple upload not allowed!');
export const uploadSizeValidation = (size = 2) => showToast('warning', 'The file size should be less then or equal ' + size + ' MB.');
export const uploadSuccess = () => showToast('success', 'The file successfully uploaded.');
export const documentDeleted = () => showToast('warning', 'Document Deleted.');

export const settingUpdated = () => showToast('warning', 'Setting Update Success.');
export const unknownError = () => showToast('danger', 'Whoops! Look like something went wrong. Try again.');
export const deleteDependent = () => showToast('info', "You can't delete this record. It's connected with other module.");

export const companyCreated = () => showToast('success', 'Company Added!');
export const companyUpdated = () => showToast('info', 'Company Updated!');
export const companyDeleted = () => showToast('warning', 'Company Deleted!');

export const unableToLoadSettings = () => showToast('danger', 'Whoops! Not able to load Settings.');
