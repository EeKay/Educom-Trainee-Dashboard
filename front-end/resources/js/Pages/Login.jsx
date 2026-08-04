import {useState, useEffect} from 'react';
import { useForm, Link } from '@inertiajs/react';
import logo from '../../img/logo.svg';
import '../../css/login.css';
import { router } from '@inertiajs/react';

const API_BASE = 'http://127.0.0.1:9000/api';

export default function Login() {

  const [loggedIn, setLoggedIn] = useState(false);
  const [login, setLogin] = useState([]);

  // get login data and store the token
  useEffect(() =>{

    fetch(`${API_BASE}/login`, {
      method: 'POST',
      headers:{
        "Content-Type": "application/json",
        "accept" : "application/json"},
      body: JSON.stringify({name: "Educom LLM", password:"12345678"})
    })

    .then (response => (response.json()))
    .then (data => {
      setLogin(data);
      console.log(data);
      const token = data.token;

      //put the token in the authentication
      return fetch(`${API_BASE}/test`, {
        method: 'GET',
        headers: {
          "Content-Type": "application/json",
          "accept": "application/json",
          "Authorization": `Bearer ${token}`
        }
      });
    })
    .then(response => response.text())   
    .then(data => {
      console.log(data);
      setLoggedIn(true);
    })
    .catch(error => console.error ("error: ", error));
    
  }, []);

  useEffect(() => {
  if (loggedIn === true) {
    router.visit('/dashboard');
  }
}, [loggedIn]);



  const { data, setData, post, processing, errors } = useForm({
    username: '',
    password: '',
  });

  function handleSubmit(e) {
    e.preventDefault();
    post('/login');
  }

  return (
    <div className="login-page">
      <div className="login-logo-corner">
        <div className="logo-group">
          <img src={logo} alt="Educom" className="logo-img" />
          <span className="logo-text">educom</span>
        </div>
      </div>

      <form className="login-card" onSubmit={handleSubmit}>
        <h1 className="login-title"><b>Welcome!</b></h1>

        <label className="login-label">Username</label>
        <input
          type="username"
          className="login-input"
          value={data.username}
          onChange={(e) => setData('username', e.target.value)}
        />
        {errors.username && <div className="login-error">{errors.username}</div>}

        <label className="login-label">Password</label>
        <input
          type="password"
          className="login-input"
          value={data.password}
          onChange={(e) => setData('password', e.target.value)}
        />
        {errors.password && <div className="login-error">{errors.password}</div>}


        {/* href = "/dashboard" if the login is correct and give key??*/}
        <button type="submit" className="login-button" disabled={processing}>
          {processing ? 'Logging in...' : 'Log in'}
        </button>


        <Link href="/passwordReset" className="forgot-password-link">
          Forgot your password?
        </Link>
      </form>       
    </div>
  );
}