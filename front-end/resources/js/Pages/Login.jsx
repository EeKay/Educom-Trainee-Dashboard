import { useForm, Link } from '@inertiajs/react';
import logo from '../../img/logo.svg';
import '../../css/login.css';

export default function Login() {
  const { data, setData, post, processing, errors } = useForm({
    email: '',
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

        <label className="login-label">Email</label>
        <input
          type="email"
          className="login-input"
          value={data.email}
          onChange={(e) => setData('email', e.target.value)}
        />
        {errors.email && <div className="login-error">{errors.email}</div>}

        <label className="login-label">Password</label>
        <input
          type="password"
          className="login-input"
          value={data.password}
          onChange={(e) => setData('password', e.target.value)}
        />
        {errors.password && <div className="login-error">{errors.password}</div>}

        <button type="submit" className="login-button" disabled={processing}>
          {processing ? 'Logging in...' : 'Log in'}
        </button>
      </form>
    </div>
  );
}