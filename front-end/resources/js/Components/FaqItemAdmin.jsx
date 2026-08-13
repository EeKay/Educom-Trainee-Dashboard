import {useState} from 'react';
import '../../css/faq-item.css';

function getCsrfToken() {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
    return match ? decodeURIComponent(match[1]) : null;
}

async function apiRequest(url, method) {
    return fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-XSRF-TOKEN': getCsrfToken(),
        },
        credentials: 'include',
    }).then((res) => {
        if (!res.ok) throw new Error(`${method} ${url} failed: ${res.status}`);
        return res.json().catch(() => null);
    });
}

export default function FaqItemAdmin({id, question, answer, isActive, onToggled, onDeleted}){
    const [isOpen, setIsOpen] = useState(false);
    const [active, setActive] = useState(isActive);
    const [saving, setSaving] = useState(false);
    const [deleting, setDeleting] = useState(false);

    const handleToggle = async () => {
        if (!id) return console.error('FaqItem: missing id');
        const next = !active;
        setActive(next);
        setSaving(true);

        try {
            await apiRequest(`/faq/${next ? 'activate' : 'deactivate'}/${id}`, 'PUT');
            onToggled?.(id, next);
        } catch (err) {
            console.error('Error toggling FAQ', err);
            setActive(!next);
        } finally {
            setSaving(false);
        }
    };

    const handleDelete = async () => {
        if (!id) return console.error('FaqItem: missing id');
        if (!window.confirm('Delete this FAQ permanently?')) return;

        setDeleting(true);
        try {
            await apiRequest(`/faq/delete/${id}`, 'DELETE');
            onDeleted?.(id);
        } catch (err) {
            console.error('Error deleting FAQ', err);
            setDeleting(false);
        }
    };

    return(
        <div className="faq-item">
            <div className="faq-item-row">
                <button className="faq-item-header" onClick={() => setIsOpen(!isOpen)}>
                    <span className={active ? 'faq-item-question' : 'faq-item-question-deactivated'}>
                        {question}
                    </span>
                    <span className={active ? 'faq-item-icon' : 'faq-item-icon-deactivated'}>
                        {isOpen ? '-' : '+'}
                    </span>
                </button>

                <button
                    type="button"
                    className="faq-item-toggle"
                    onClick={handleToggle}
                    disabled={saving || deleting}
                    role="switch"
                    aria-checked={active}
                    aria-label={active ? 'Deactivate this question' : 'Activate this question'}
                >
                    <span className={`faq-item-toggle-label ${active ? 'is-active' : ''}`}>
                        {active ? 'Active' : 'Inactive'}
                    </span>
                    <span className={`faq-item-toggle-track ${active ? 'is-active' : ''}`}>
                        <span className="faq-item-toggle-thumb"></span>
                    </span>
                </button>

                <button
                    type="button"
                    className="faq-item-delete"
                    onClick={handleDelete}
                    disabled={saving || deleting}
                    aria-label="Delete this question"
                    title="Delete"
                >
                    {deleting ? '…' : '✕'}
                </button>
            </div>

            <div className={`faq-item-answer ${isOpen ? 'faq-item-answer-open' : ''}`}>
                <div className={active ? 'faq-item-answer-inner' : 'faq-item-answer-inner-deactivated'}>
                    {answer}
                </div>
            </div>
        </div>
    )
}