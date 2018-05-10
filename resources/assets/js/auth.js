class Auth {
    constructor() {
        this.token = null;
        this.user = null;
    }

    login (token, user) {
        window.localStorage.setItem('token', token);
        window.localStorage.setItem('user', JSON.stringify(user));

        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;

        this.token = token;
        this.user = user;

        Event.$emit('userLoggedIn');
    }

    logout (token, user) {
        axios.post('/api/logout')
            .then(({data}) => {
                window.localStorage.setItem('token', null);
                window.localStorage.setItem('user', null);
                axios.defaults.headers.common['Authorization'] = '';
                this.token = null;
                this.user = null;
                Event.$emit('userLoggedOut');
            })
            .catch(({response}) => {
                alert(response.data.message);
            });
    }

    check () {
        return !! this.token;
    }
}

export default new Auth();