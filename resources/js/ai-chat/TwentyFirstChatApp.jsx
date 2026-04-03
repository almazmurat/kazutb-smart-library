import { AgentChat, createAgentChat } from '@21st-sdk/react';
import { useChat } from '@ai-sdk/react';
import { useEffect, useMemo, useState } from 'react';

const storageKeyFor = (agent) => `21st-chat:${agent}`;

function readStoredSession(agent) {
    try {
        const raw = window.localStorage.getItem(storageKeyFor(agent));
        if (!raw) {
            return null;
        }

        const parsed = JSON.parse(raw);

        if (!parsed?.sandboxId || !parsed?.threadId) {
            return null;
        }

        return parsed;
    } catch {
        return null;
    }
}

function writeStoredSession(agent, session) {
    window.localStorage.setItem(storageKeyFor(agent), JSON.stringify(session));
}

async function postJson(url, body) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(body),
        credentials: 'same-origin',
    });

    const payload = await response.json().catch(() => ({}));

    if (!response.ok) {
        throw new Error(payload.message ?? 'Request failed');
    }

    return payload;
}

function ChatRuntime({ agent, tokenUrl, session, onNewThread }) {
    const chat = useMemo(
        () => createAgentChat({
            agent,
            tokenUrl,
            sandboxId: session.sandboxId,
            threadId: session.threadId,
        }),
        [agent, tokenUrl, session.sandboxId, session.threadId],
    );

    const { messages, sendMessage, status, stop, error } = useChat({ chat });

    return (
        <div className="flex min-h-[680px] flex-col rounded-[28px] border border-slate-200 bg-white/90 shadow-[0_24px_80px_rgba(15,23,42,0.12)] backdrop-blur">
            <div className="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                <div>
                    <p className="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">21st Frontend Agent</p>
                    <p className="mt-1 text-sm text-slate-600">Sandbox {session.sandboxId.slice(0, 12)}... · Thread {session.threadId.slice(0, 12)}...</p>
                </div>
                <button
                    type="button"
                    onClick={onNewThread}
                    className="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-200 px-4 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                >
                    New thread
                </button>
            </div>
            <div className="min-h-0 flex-1">
                <AgentChat
                    messages={messages}
                    onSend={(message) =>
                        sendMessage({
                            role: 'user',
                            parts: [{ type: 'text', text: message.content }],
                        })
                    }
                    status={status}
                    onStop={stop}
                    error={error ?? undefined}
                    colorMode="light"
                    theme={{
                        light: {
                            '--an-chat-accent': '#124559',
                            '--an-chat-accent-foreground': '#ffffff',
                            '--an-chat-background': '#ffffff',
                            '--an-chat-surface': '#f8fafc',
                            '--an-chat-surface-2': '#eef2f7',
                            '--an-chat-border': '#d7e0ea',
                            '--an-chat-text': '#0f172a',
                            '--an-chat-text-muted': '#64748b',
                        },
                    }}
                />
            </div>
        </div>
    );
}

export function TwentyFirstChatApp({ agent, sessionUrl, tokenUrl, threadUrl, staffName }) {
    const [session, setSession] = useState(() => readStoredSession(agent));
    const [bootstrapError, setBootstrapError] = useState('');
    const [isBootstrapping, setIsBootstrapping] = useState(() => !readStoredSession(agent));
    const [threadCounter, setThreadCounter] = useState(0);

    useEffect(() => {
        if (session) {
            return;
        }

        let isMounted = true;

        const bootstrap = async () => {
            try {
                setBootstrapError('');
                const payload = await postJson(sessionUrl, {
                    name: 'Frontend chat',
                });

                if (!isMounted) {
                    return;
                }

                const nextSession = {
                    sandboxId: payload.sandboxId,
                    threadId: payload.threadId,
                };

                writeStoredSession(agent, nextSession);
                setSession(nextSession);
            } catch (error) {
                if (!isMounted) {
                    return;
                }

                setBootstrapError(error instanceof Error ? error.message : 'Unable to initialize the AI chat.');
            } finally {
                if (isMounted) {
                    setIsBootstrapping(false);
                }
            }
        };

        bootstrap();

        return () => {
            isMounted = false;
        };
    }, [agent, session, sessionUrl]);

    const createNewThread = async () => {
        if (!session) {
            return;
        }

        try {
            setBootstrapError('');
            const payload = await postJson(threadUrl, {
                sandboxId: session.sandboxId,
                name: 'Frontend follow-up',
            });

            const nextSession = {
                sandboxId: session.sandboxId,
                threadId: payload.threadId,
            };

            writeStoredSession(agent, nextSession);
            setSession(nextSession);
            setThreadCounter((value) => value + 1);
        } catch (error) {
            setBootstrapError(error instanceof Error ? error.message : 'Unable to create a new thread.');
        }
    };

    return (
        <section className="min-h-screen bg-[linear-gradient(180deg,#f8fafc_0%,#eef4f3_100%)] px-4 py-8 text-slate-900 sm:px-6 lg:px-8">
            <div className="mx-auto flex w-full max-w-7xl flex-col gap-6">
                <header className="rounded-[28px] border border-slate-200 bg-white/85 px-6 py-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] backdrop-blur">
                    <div className="flex flex-wrap items-start justify-between gap-6">
                        <div className="max-w-3xl">
                            <p className="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-slate-600">Internal Tooling</p>
                            <h1 className="mt-4 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">21st AI chat for frontend development</h1>
                            <p className="mt-3 text-base leading-7 text-slate-600">
                                Internal staff-only assistant for UI work inside the current Laravel + Blade + Vite stack. It keeps the 21st API key on the server,
                                creates a sandbox and thread via Laravel bridge endpoints, and connects the browser chat with short-lived tokens.
                            </p>
                        </div>
                        <div className="min-w-[240px] rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-600">
                            <p className="font-semibold text-slate-900">Signed in staff</p>
                            <p className="mt-1">{staffName}</p>
                            <p className="mt-3 text-xs uppercase tracking-[0.18em] text-slate-500">Agent</p>
                            <p className="mt-1 font-mono text-xs text-slate-700">{agent}</p>
                        </div>
                    </div>
                </header>

                {bootstrapError ? (
                    <div className="rounded-2xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                        {bootstrapError}
                    </div>
                ) : null}

                {isBootstrapping || !session ? (
                    <div className="rounded-[28px] border border-slate-200 bg-white px-6 py-12 text-center text-slate-600 shadow-[0_24px_80px_rgba(15,23,42,0.08)]">
                        Preparing sandbox and chat thread...
                    </div>
                ) : (
                    <ChatRuntime
                        key={`${session.sandboxId}:${session.threadId}:${threadCounter}`}
                        agent={agent}
                        tokenUrl={tokenUrl}
                        session={session}
                        onNewThread={createNewThread}
                    />
                )}
            </div>
        </section>
    );
}