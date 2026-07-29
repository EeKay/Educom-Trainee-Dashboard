import { createContext, useContext, useState, useEffect } from 'react';

const AuthContext = createContext(null);

export function AuthProvider({children}){
    const [token, setToken] = useState(() => localStorage.getItem('auth_token'));

    useEffect(() => {
        if (token) {
            localStorage.setItem('auth_token', token);
        } else {
            localStorage.removeItem('auth_token')
        }
    }, [token]);

    function login(newToken){
        setToken(newToken);
    }

    function logout(){
        setToken(null);
    }

    return(
        <AuthContext.Provider value={{ token, login, logout, isLoggedIn: !!token }}>
            {children}
        </AuthContext.Provider>
    );
}

export function useAuth() {
    return useContext(AuthContext);
}