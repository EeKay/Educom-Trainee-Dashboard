import { useForm, Link } from '@inertiajs/react';
import logo from '../../img/logo.svg';
import '../../css/password-reset.css';

export default function PasswordReset() {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
    });

    function handleSubmit(e) {
        e.preventDefault();
        post('/forgot-password');
    }

    return (
        <div className="reset-page">
            <div className="login-logo-corner">
                <div className="logo-group">
                    <img src={logo} alt="Educom" className="logo-img" />
                    <span className="logo-text">educom</span>
                </div>
            </div>

            <form className="reset-card" onSubmit={handleSubmit}>
                <h1 className="reset-title">
                    <b>Reset Password</b>
                </h1>

                <p className="reset-description">
                    Enter your email address and we'll send you a password reset link.
                </p>

                <label className="reset-label">Email</label>

                <input
                    type="email"
                    className="reset-input"
                    value={data.email}
                    onChange={(e) => setData('email', e.target.value)}
                />

                {errors.email && (
                    <div className="reset-error">{errors.email}</div>
                )}

                <button
                    className="reset-button"
                    type="submit"
                    disabled={processing}
                >
                    {processing ? "Sending..." : "Send Reset Link"}
                </button>

                <Link href="/login" className="back-link">
                    ← Back to login
                </Link>
            </form>
        </div>
    );
}
