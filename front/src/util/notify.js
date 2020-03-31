import {notification} from 'ant-design-vue';

export const loginSuccess = () => notification['success']({ message: 'Welcome! Login Success', description: '' });
export const loginFailed = () => notification['error']({ message: 'Login failed! Make sure email verified.', description: '' });
export const registerSuccess = () => notification['success']({ message: 'Congratulations! Register Success.', description: 'Verify Email! So can login.' });
export const registerFailed = () => notification['error']({ message: 'Registration failed!', description: '' });

export const sessionExpires = () => notification['error']({ message: 'Session expired!', description: '' });
export const serverError = () => notification['error']({ message: 'Whoops! Server Error!', description: 'Please refresh the page and try again!' });
export const forgotPasswordSuccess = () => notification['info']({ message: 'Check inbox! We have sent you email.', description: '' });
export const resetPasswordSuccess = () => notification['info']({ message: 'Congratulations! Reset password successful.', description: '' });

export const userCreated = () => notification['success']({ message: 'User Added!', description: '' });
export const userUpdated = () => notification['info']({ message: 'User Updated!', description: '' });
export const userDeleted = () => notification['warning']({ message: 'User Deleted!', description: '' });

export const experienceLevelCreated = () => notification['success']({ message: 'Experience Level Added!', description: '' });
export const experienceLevelUpdated = () => notification['info']({ message: 'Experience Level Updated!', description: '' });
export const experienceLevelDeleted = () => notification['warning']({ message: 'Experience Level Deleted!', description: '' });

export const qualificationCreated = () => notification['success']({ message: 'Qualification Added!', description: '' });
export const qualificationUpdated = () => notification['info']({ message: 'Qualification Updated!', description: '' });
export const qualificationDeleted = () => notification['warning']({ message: 'Qualification Deleted!', description: '' });

export const interviewStageCreated = () => notification['success']({ message: 'Interview Stage Added!', description: '' });
export const interviewStageUpdated = () => notification['info']({ message: 'Interview Stage Updated!', description: '' });
export const interviewStageDeleted = () => notification['warning']({ message: 'Interview Stage Deleted!', description: '' });

export const jobDepartmentCreated = () => notification['success']({ message: 'Job Department Added!', description: '' });
export const jobDepartmentUpdated = () => notification['info']({ message: 'Job Department Updated!', description: '' });
export const jobDepartmentDeleted = () => notification['warning']({ message: 'Job Department Deleted!', description: '' });

export const verifyEmailSuccess = () => notification['info']({ message: 'Congratulations! Email Verification Success', description: '' });
export const verifyEmailFailed = () => notification['error']({ message: 'Whoops! Looks like token mismatch.', description: '' });

export const jobApplySuccess = () => notification['info']({ message: 'Congratulations! You have applied for a job.', description: '' });
export const jobApplyFailed = () => notification['error']({ message: 'Whoops! Looks like fields mismatch.', description: '' });
export const jobApplyAlready = () => notification['info']({ message: 'Whoops! Looks like You already applied.', description: '' });

export const profileUpdated = () => notification['info']({ message: 'Profile update successful.', description: '' });

export const jobCreated = () => notification['success']({ message: 'Job Added!', description: '' });
export const jobUpdated = () => notification['info']({ message: 'Job Updated!', description: '' });
export const jobDeleted = () => notification['warning']({ message: 'Job Deleted!', description: '' });
export const jobArchived = () => notification['warning']({ message: 'Job Archived!', description: '' });

export const jobApplicationCreated = () => notification['success']({ message: 'Job Application Added!', description: '' });
export const jobApplicationUpdated = () => notification['info']({ message: 'Job Application Updated!', description: '' });

export const interviewCreated = () => notification['success']({ message: 'Interview Added!', description: '' });
export const interviewUpdated = () => notification['info']({ message: 'Interview Updated!', description: '' });
export const interviewDeleted = () => notification['warning']({ message: 'Interview Deleted!', description: '' });

export const documentTypeCreated = () => notification['success']({ message: 'Document Type Added!', description: '' });
export const documentTypeUpdated = () => notification['info']({ message: 'Document Type Updated!', description: '' });
export const documentTypeDeleted = () => notification['warning']({ message: 'Document Type Deleted!', description: '' });

export const moreThenOneUploadNotAllowed = () => notification['warning']({ message: 'Multiple upload not allowed!', description: '' });
export const uploadSizeValidation = (size = 2) => notification['warning']({ message: 'The file size should be less then or equal '+size+' MB.', description: '' });
export const uploadSuccess = () => notification['success']({ message: 'The file successfully uploaded.', description: '' });
export const documentDeleted = () => notification['warning']({ message: 'Document Deleted.', description: '' });

export const settingUpdated = () => notification['warning']({ message: 'Setting Update Success.', description: '' });
export const unknownError = () => notification['error']({ message: 'Whoops! Look like something went wrong. Try again.', description: '' });
export const deleteDependent = () => notification['info']({ message: "You can't delete this record. It's connected with other module.", description: '' });

export const companyCreated = () => notification['success']({ message: 'Company Added!', description: '' });
export const companyUpdated = () => notification['info']({ message: 'Company Updated!', description: '' });
export const companyDeleted = () => notification['warning']({ message: 'Company Deleted!', description: '' });

export const unableToLoadSettings = () => notification['error']({ message: 'Whoops! Not able to load Settings.', description: '' });
