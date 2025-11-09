import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler, useState } from 'react';
import { ArrowLeft, Info } from 'lucide-react';

export default function Create() {
    const [bulkMode, setBulkMode] = useState(false);
    const { data, setData, post, processing, errors } = useForm({
        type: 'deposit',
        amount: '',
        expires_in_hours: '24',
        notes: '',
        quantity: '5',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const endpoint = bulkMode ? '/admin/codes/bulk-generate' : '/admin/codes';
        post(endpoint);
    };

    return (
        <AdminLayout>
            <Head title="Generate Transaction Code" />
            
            <div className="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-8">
                <div className="w-full max-w-2xl space-y-6">
                    <div className="text-center sm:text-left">
                        <Link href="/admin/codes" className="inline-flex items-center text-sm text-slate-400 hover:text-slate-50 mb-4">
                            <ArrowLeft className="h-4 w-4 mr-1" />
                            Back to Codes
                        </Link>
                        <h1 className="text-2xl sm:text-3xl font-bold text-slate-50">Generate Transaction Code</h1>
                        <p className="text-slate-400 mt-1 text-sm sm:text-base">Create authorization codes for transactions</p>
                    </div>

                    <Card className="bg-slate-900 border-slate-800 max-w-2xl mx-auto">
                    <CardHeader>
                        <CardTitle className="text-slate-50">Code Details</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={submit} className="space-y-4">
                            {/* Bulk Mode Toggle */}
                            <div className="flex items-center space-x-2 pb-4 border-b border-slate-800">
                                <input
                                    id="bulk_mode"
                                    type="checkbox"
                                    checked={bulkMode}
                                    onChange={(e) => setBulkMode(e.target.checked)}
                                    className="h-4 w-4 rounded border-slate-700 bg-slate-800 text-slate-400 focus:ring-slate-600"
                                />
                                <label htmlFor="bulk_mode" className="text-sm text-slate-300">
                                    Generate multiple codes at once
                                </label>
                            </div>

                            {/* Quantity (bulk only) */}
                            {bulkMode && (
                                <div>
                                    <label htmlFor="quantity" className="block text-sm font-medium text-slate-300 mb-1">
                                        Quantity <span className="text-red-400">*</span>
                                    </label>
                                    <input
                                        id="quantity"
                                        type="number"
                                        min="1"
                                        max="50"
                                        value={data.quantity}
                                        onChange={(e) => setData('quantity', e.target.value)}
                                        className="w-full rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-600"
                                    />
                                    <p className="mt-1 text-xs text-slate-500">Maximum 50 codes at once</p>
                                </div>
                            )}

                            <div>
                                <label htmlFor="type" className="block text-sm font-medium text-slate-300 mb-1">
                                    Type <span className="text-red-400">*</span>
                                </label>
                                <select
                                    id="type"
                                    value={data.type}
                                    onChange={(e) => setData('type', e.target.value)}
                                    className="w-full rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-600"
                                    required
                                >
                                    <option value="deposit">Deposit</option>
                                    <option value="withdrawal">Withdrawal</option>
                                    <option value="transfer">Transfer</option>
                                </select>
                                {errors.type && <p className="mt-1 text-sm text-red-400">{errors.type}</p>}
                            </div>

                            <div>
                                <label htmlFor="amount" className="block text-sm font-medium text-slate-300 mb-1">
                                    Amount ($) <span className="text-slate-500">(Optional)</span>
                                </label>
                                <input
                                    id="amount"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    value={data.amount}
                                    onChange={(e) => setData('amount', e.target.value)}
                                    className="w-full rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-600"
                                    placeholder="Leave empty for any amount"
                                />
                                <p className="mt-1 text-xs text-slate-500">Leave empty to allow any amount, or specify a fixed amount</p>
                                {errors.amount && <p className="mt-1 text-sm text-red-400">{errors.amount}</p>}
                            </div>

                            <div>
                                <label htmlFor="expires_in_hours" className="block text-sm font-medium text-slate-300 mb-1">
                                    Expires In <span className="text-red-400">*</span>
                                </label>
                                <select
                                    id="expires_in_hours"
                                    value={data.expires_in_hours}
                                    onChange={(e) => setData('expires_in_hours', e.target.value)}
                                    className="w-full rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-600"
                                    required
                                >
                                    <option value="1">1 Hour</option>
                                    <option value="3">3 Hours</option>
                                    <option value="6">6 Hours</option>
                                    <option value="12">12 Hours</option>
                                    <option value="24">24 Hours</option>
                                    <option value="48">48 Hours</option>
                                    <option value="72">3 Days</option>
                                    <option value="168">1 Week</option>
                                </select>
                                {errors.expires_in_hours && <p className="mt-1 text-sm text-red-400">{errors.expires_in_hours}</p>}
                            </div>

                            {!bulkMode && (
                                <div>
                                    <label htmlFor="notes" className="block text-sm font-medium text-slate-300 mb-1">
                                        Notes (Optional)
                                    </label>
                                    <textarea
                                        id="notes"
                                        value={data.notes}
                                        onChange={(e) => setData('notes', e.target.value)}
                                        rows={3}
                                        className="w-full rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-600"
                                        placeholder="Add any notes about this code..."
                                    />
                                </div>
                            )}

                            <div className="bg-blue-900/20 border border-blue-800 rounded-lg p-4">
                                <div className="flex">
                                    <Info className="h-5 w-5 text-blue-400 mr-3 flex-shrink-0" />
                                    <div className="text-sm text-blue-200">
                                        <p className="font-medium mb-1">How Transaction Codes Work</p>
                                        <ul className="list-disc list-inside space-y-1 text-xs text-blue-300">
                                            <li>Users enter these codes to authorize deposits or withdrawals</li>
                                            <li>Each code can only be used once</li>
                                            <li>Codes automatically expire after the specified time</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div className="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-4">
                                <Link href="/admin/codes" className="w-full sm:w-auto">
                                    <Button type="button" variant="outline" className="w-full bg-slate-800 border-slate-700 hover:bg-slate-700 text-slate-50">
                                        Cancel
                                    </Button>
                                </Link>
                                <Button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full sm:w-auto bg-slate-700 hover:bg-slate-600 text-slate-50"
                                >
                                    {processing ? 'Generating...' : bulkMode ? 'Generate Codes' : 'Generate Code'}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
                </div>
            </div>
        </AdminLayout>
    );
}


