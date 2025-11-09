import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Head, Link, useForm } from '@inertiajs/react';
import { ArrowLeft, Send } from 'lucide-react';
import { TransactionCode, PageProps, User } from '@/types';
import { FormEvent } from 'react';

export default function Show({ code, users }: PageProps & { code: TransactionCode; users: User[] }) {
    const isExpired = new Date(code.expires_at) < new Date();
    const isActive = !code.is_used && !isExpired;

    const sendForm = useForm({
        user_id: '',
    });

    const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        if (!isActive) {
            return;
        }

        sendForm.post(`/admin/codes/${code.id}/send`, {
            preserveScroll: true,
            onSuccess: () => sendForm.reset(),
        });
    };

    const formatAmount = (amount?: number | string | null) => {
        if (amount === null || amount === undefined) {
            return 'Any Amount';
        }

        const numeric = typeof amount === 'string' ? parseFloat(amount) : amount;

        if (!Number.isFinite(numeric)) {
            return 'Any Amount';
        }

        return `$${numeric.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
    };

    return (
        <AdminLayout>
            <Head title={`Code: ${code.code}`} />
            
            <div className="space-y-6">
                <div>
                    <Link href="/admin/codes" className="inline-flex items-center text-sm text-slate-400 hover:text-slate-50 mb-4">
                        <ArrowLeft className="h-4 w-4 mr-1" />
                        Back to Codes
                    </Link>
                    <h1 className="text-3xl font-bold text-slate-50 font-mono">{code.code}</h1>
                    <p className="text-slate-400 mt-1">Transaction code details</p>
                </div>

                <div className="grid gap-4 lg:grid-cols-2">
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Code Information</CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div>
                                <p className="text-sm text-slate-400">Code</p>
                                <p className="text-2xl font-mono font-bold text-slate-50">{code.code}</p>
                            </div>
                            <div>
                                <p className="text-sm text-slate-400">Type</p>
                                <Badge className={
                                    code.type === 'deposit' ? 'bg-green-900/50 text-green-200 border-green-700' :
                                    code.type === 'withdrawal' ? 'bg-red-900/50 text-red-200 border-red-700' :
                                    'bg-blue-900/50 text-blue-200 border-blue-700'
                                }>
                                    {code.type}
                                </Badge>
                            </div>
                            <div>
                                <p className="text-sm text-slate-400">Amount</p>
                                <p className="text-xl font-bold text-slate-50">
                                    {formatAmount(code.amount)}
                                </p>
                            </div>
                            <div>
                                <p className="text-sm text-slate-400">Status</p>
                                {code.is_used ? (
                                    <Badge className="bg-slate-800 text-slate-300 border-slate-700">Used</Badge>
                                ) : isExpired ? (
                                    <Badge className="bg-red-900/50 text-red-200 border-red-700">Expired</Badge>
                                ) : (
                                    <Badge className="bg-emerald-900/50 text-emerald-200 border-emerald-700">Active</Badge>
                                )}
                            </div>
                            <div>
                                <p className="text-sm text-slate-400">Created By</p>
                                <p className="text-slate-50">{code.creator?.name || 'N/A'}</p>
                            </div>
                            <div>
                                <p className="text-sm text-slate-400">Expires At</p>
                                <p className="text-slate-50">{new Date(code.expires_at).toLocaleString()}</p>
                            </div>
                            {code.is_used && (
                                <>
                                    <div>
                                        <p className="text-sm text-slate-400">Used By</p>
                                        <p className="text-slate-50">{code.usedBy?.name || 'N/A'}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm text-slate-400">Used At</p>
                                        <p className="text-slate-50">{code.used_at ? new Date(code.used_at).toLocaleString() : 'N/A'}</p>
                                    </div>
                                </>
                            )}
                            {code.notes && (
                                <div>
                                    <p className="text-sm text-slate-400">Notes</p>
                                    <p className="text-slate-50">{code.notes}</p>
                                </div>
                            )}
                        </CardContent>
                    </Card>

                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Send Code to User</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <form onSubmit={handleSubmit} className="space-y-4">
                                <div>
                                    <label htmlFor="user" className="block text-sm font-medium text-slate-300 mb-2">
                                        Select Recipient
                                    </label>
                                    <select
                                        id="user"
                                        value={sendForm.data.user_id}
                                        onChange={(event) => sendForm.setData('user_id', event.target.value)}
                                        className="w-full rounded-lg border border-slate-700 bg-slate-950/60 px-3 py-2 text-sm text-slate-100 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/40"
                                        disabled={!isActive || sendForm.processing}
                                    >
                                        <option value="">Choose a user</option>
                                        {users.map((user) => (
                                            <option key={user.id} value={user.id}>
                                                {user.name} ({user.email})
                                            </option>
                                        ))}
                                    </select>
                                    {sendForm.errors.user_id && (
                                        <p className="mt-2 text-xs text-rose-300">{sendForm.errors.user_id}</p>
                                    )}
                                </div>

                                {!isActive && (
                                    <p className="text-sm text-amber-200">
                                        Only active codes can be sent to users.
                                    </p>
                                )}

                                <Button
                                    type="submit"
                                    disabled={!isActive || sendForm.processing || !sendForm.data.user_id}
                                    className="inline-flex items-center justify-center bg-amber-600 hover:bg-amber-700 text-white"
                                >
                                    <Send className="mr-2 h-4 w-4" />
                                    {sendForm.processing ? 'Sending...' : 'Send Code'}
                                </Button>
                            </form>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AdminLayout>
    );
}


