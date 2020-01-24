import Storage from './storage'
export default {
    mixins: ['Storage'],
    data(){
        return {
            session:{}
        }
    },
    checkAuth() {
        this.session = Storage.get('session');
        
        if (this.session && typeof this.session.authenticated != 'undefined' && this.session.authenticated == true) {
            return true;
        } else {
            return false;
        }
    },
    can(roles) {
        if (this.checkAuth()) {
            for(let i in roles){
                if(this.session.role == roles[i]){
                    return true
                }            
            }
        }
        return false;
    },
    getUser(){
        if (this.checkAuth()) {
            return this.session.user;
        }
        return {};
    },
    setUser(user){
        if (this.checkAuth()) {
            for(let i in user){
                if(typeof this.session.user[i] != 'undefined'){
                    this.session.user[i] = user[i];
                }
            }
            Storage.set('session',this.session);
            return this.session.user;
        }
    },
    getAccount(){
        if (this.checkAuth()) {
            return this.session.account;
        }
        return {};
    },
    setAccount(account){
        if (this.checkAuth()) {
            if(typeof this.session.account != 'undefined'){
                for(let i in account){
                    if(typeof this.session.account[i] != 'undefined'){
                        this.session.account[i] = account[i];                        
                    }
                }
            }else{
                this.session.account = account;
            }
            Storage.set('session',this.session);
            return this.session.account;
        }
    },
    getAccessToken(){
        if (this.checkAuth()) {
            return this.session.auth.access_token;
        }
        return '';
    },
    getTokens(){
        if (this.checkAuth()) {
            return this.session.auth;
        }
        return {};
    },
    getRole(){
        if (this.checkAuth()) {
            return this.session.role;
        }
        return '';
    },
    setSession(data){
        if (typeof data.user != 'undefined' && typeof data.auth != 'undefined' && typeof data.role != 'undefined') {
            this.session = data;
            this.session.authenticated = true;
            Storage.set('session',this.session);
        }
        return this.session;        
    },
    removeSession(){
        Storage.remove('session');
        this.session = {};
    }
    
}