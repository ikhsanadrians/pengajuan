import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const loadToastMessage = (toastSeverity, toastSummary, toastMessageDetail) => {
    toast.add({ severity: toastSeverity, summary: toastSummary, detail: toastMessageDetail, group: 'br', life: 3000 });
};

export default loadToastMessage;
