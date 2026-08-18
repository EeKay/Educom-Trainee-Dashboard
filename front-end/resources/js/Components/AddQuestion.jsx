import '../../css/add-question.css';
import { useState } from 'react';

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
        if (!res.ok) {
            throw new Error(`${method} ${url} failed: ${res.status}`);
        }

        return res.json().catch(() => null);
    });
}

export default function AddQuestion({ onSubmitted }) {
    const [question, setQuestion] = useState('');
    const [answer, setAnswer] = useState('');
    const [submitting, setSubmitting] = useState(false);

    const handleAdded = async (e) => {
        e.preventDefault();

        if (!question.trim()) {
            return console.error('FAQ missing question');
        }

        if (!answer.trim()) {
            return console.error('FAQ missing answer');
        }

        setSubmitting(true);

        try {
            const newFaq = await apiRequest(
                `/faq/create?question=${encodeURIComponent(question)}&answer=${encodeURIComponent(answer)}`,
                'POST'
            );

            // If your Laravel API returns { data: {...} }
            const faq = newFaq?.data ?? newFaq;

            onSubmitted?.(faq);

            setQuestion('');
            setAnswer('');
        } catch (err) {
            console.error('Error submitting FAQ', err);
        } finally {
            setSubmitting(false);
        }
    };

    return (
        <form className="add-question" onSubmit={handleAdded}>
            <h2 className="add-question-title"> Add a new FAQ item</h2>
            
            <input
                value={question}
                onChange={(e) => setQuestion(e.target.value)}
                placeholder="Question"
                disabled={submitting}
            />

            <textarea
                value={answer}
                onChange={(e) => setAnswer(e.target.value)}
                placeholder="Answer"
            />

            <button type="submit" disabled={submitting}>
                {submitting ? 'Submitting...' : 'Submit'}
            </button>
        </form>
    );
}