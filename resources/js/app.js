import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('quoteForm', (defaultService) => ({
    loading: false,
    message: '',
    isSuccess: false,
    isDragging: false,
    file: null,
    formData: {
        company_name: '',
        name: '',
        email: '',
        service_type: defaultService,
        message: ''
    },
    submitForm() {
        this.loading = true;
        this.message = '';
        this.isSuccess = false;

        const data = new FormData();
        data.append('company_name', this.formData.company_name);
        data.append('name', this.formData.name);
        data.append('email', this.formData.email);
        data.append('service_type', this.formData.service_type);
        data.append('message', this.formData.message);
        if (this.file) {
            data.append('file', this.file);
        }

        window.axios.post('/quote', data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then(response => {
                this.isSuccess = true;
                this.message = response.data.message;
                this.formData = {
                    company_name: '',
                    name: '',
                    email: '',
                    service_type: defaultService,
                    message: ''
                };
                this.file = null;
                setTimeout(() => {
                    window.quoteModalOpen = false;
                    this.message = '';
                }, 3000);
            })
            .catch(error => {
                this.isSuccess = false;
                this.message = error.response?.data?.message || 'Bir hata oluştu. Lütfen tekrar deneyin.';
            })
            .finally(() => {
                this.loading = false;
            });
    }
}));

Alpine.start();


