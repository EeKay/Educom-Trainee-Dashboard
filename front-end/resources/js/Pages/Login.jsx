import { useState } from 'react';
import { useForm, Link, router } from '@inertiajs/react';
import logo from '../../img/logo.svg';
import '../../css/login.css';

const API_BASE = 'http://127.0.0.1:9000/api';

export default function Login(props) {
  const { data, setData, post, processing, errors } = useForm({
    name: '',
    password: '',
  });

  function handleSubmit(e) {
    e.preventDefault();
    post(`/login`);
  }
    
  const token = props.token;

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
          type="text"
          className="login-input"
          value={data.name}
          onChange={(e) => setData('name', e.target.value)}
        />
        {errors.name && <div className="login-error">{errors.name}</div>}

        <label className="login-label">Password</label>
        <input
          type="password"
          className="login-input"
          value={data.password}
          onChange={(e) => setData('password', e.target.value)}
        />
        {errors.password && <div className="login-error">{errors.password}</div>}

        {props.errorMessage && <div className="login-error">{props.errorMessage}</div>}

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