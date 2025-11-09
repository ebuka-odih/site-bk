import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Head, Link, useForm } from '@inertiajs/react';
import { PageProps } from '@/types';
import { Plus, CreditCard, Edit, Trash2, Power } from 'lucide-react';

interface PaymentMethod {
    id: number;
    type: string;
    name: string;
    key: string;
    enabled: boolean;
    min_amount: number;
    max_amount: number | null;
    processing_time: string | null;
    fee_percentage: number | null;
    fee_fixed: number | null;
    requires_reference: boolean;
    sort_order: number;
    created_at: string;
    updated_at: string;
}

export default function Index({ paymentMethods }: PageProps & { paymentMethods: PaymentMethod[] }) {
    const { delete: destroy, post } = useForm();

    const handleDelete = (id: number) => {
        if (confirm('Are you sure you want to delete this payment method?')) {
            destroy(`/admin/payment-methods/${id}`, {
                preserveScroll: true,
            });
        }
    };

    const handleToggleStatus = (id: number) => {
        post(`/admin/payment-methods/${id}/toggle-status`, {
            preserveScroll: true,
        });
    };

    const getTypeBadge = (type: string) => {
        const colors: Record<string, string> = {
            bank: 'bg-blue-900/50 text-blue-200 border-blue-700',
            crypto: 'bg-purple-900/50 text-purple-200 border-purple-700',
            paypal: 'bg-indigo-900/50 text-indigo-200 border-indigo-700',
            wire_transfer: 'bg-cyan-900/50 text-cyan-200 border-cyan-700',
            custom: 'bg-slate-900/50 text-slate-200 border-slate-700',
        };
        return colors[type] || colors.custom;
    };

    const formatCurrency = (amount: number) => {
        return `$${(amount / 100).toFixed(2)}`;
    };

    return (
        <AdminLayout>
            <Head title="Payment Methods" />
            
            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-3xl font-bold text-slate-50">Payment Methods</h1>
                        <p className="text-slate-400 mt-1">Configure deposit payment methods for users</p>
                    </div>
                    <Link href="/admin/payment-methods/create">
                        <Button className="bg-amber-600 hover:bg-amber-700 text-white shadow-lg shadow-amber-900/50 hover:shadow-xl hover:shadow-amber-900/60 transition-all">
                            <Plus className="h-4 w-4 mr-2" />
                            Add Payment Method
                        </Button>
                    </Link>
                </div>

                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50">All Payment Methods</CardTitle>
                        <CardDescription className="text-slate-400">
                            Manage deposit methods available to users
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead>
                                    <tr className="border-b border-slate-800">
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Name</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Type</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Min Amount</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Processing Time</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Fee</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Status</th>
                                        <th className="text-right py-3 px-4 text-sm font-medium text-slate-400">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {paymentMethods.map((method) => (
                                        <tr key={method.id} className="border-b border-slate-800 hover:bg-slate-800/50">
                                            <td className="py-3 px-4">
                                                <div className="flex items-center gap-3">
                                                    <CreditCard className="h-5 w-5 text-slate-400" />
                                                    <div>
                                                        <p className="text-sm font-medium text-slate-50">{method.name}</p>
                                                        <p className="text-xs text-slate-500">{method.key}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td className="py-3 px-4">
                                                <Badge className={getTypeBadge(method.type)}>
                                                    {method.type.replace('_', ' ')}
                                                </Badge>
                                            </td>
                                            <td className="py-3 px-4 text-sm text-slate-50">
                                                {formatCurrency(method.min_amount)}
                                            </td>
                                            <td className="py-3 px-4 text-sm text-slate-400">
                                                {method.processing_time || 'Instant'}
                                            </td>
                                            <td className="py-3 px-4 text-sm text-slate-400">
                                                {method.fee_percentage ? `${method.fee_percentage}%` : 
                                                 method.fee_fixed ? formatCurrency(method.fee_fixed) : 
                                                 'Free'}
                                            </td>
                                            <td className="py-3 px-4">
                                                <Badge className={method.enabled ? 
                                                    'bg-emerald-900/50 text-emerald-200 border-emerald-700' : 
                                                    'bg-slate-800 text-slate-300 border-slate-700'
                                                }>
                                                    {method.enabled ? 'Enabled' : 'Disabled'}
                                                </Badge>
                                            </td>
                                            <td className="py-3 px-4">
                                                <div className="flex items-center justify-end gap-2">
                                                    <Button
                                                        size="sm"
                                                        variant="ghost"
                                                        onClick={() => handleToggleStatus(method.id)}
                                                        className="text-slate-400 hover:text-slate-50"
                                                    >
                                                        <Power className="h-4 w-4" />
                                                    </Button>
                                                    <Link href={`/admin/payment-methods/${method.id}/edit`}>
                                                        <Button
                                                            size="sm"
                                                            variant="ghost"
                                                            className="text-slate-400 hover:text-slate-50"
                                                        >
                                                            <Edit className="h-4 w-4" />
                                                        </Button>
                                                    </Link>
                                                    <Button
                                                        size="sm"
                                                        variant="ghost"
                                                        onClick={() => handleDelete(method.id)}
                                                        className="text-red-400 hover:text-red-300"
                                                    >
                                                        <Trash2 className="h-4 w-4" />
                                                    </Button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>

                            {paymentMethods.length === 0 && (
                                <div className="text-center py-12">
                                    <CreditCard className="h-12 w-12 text-slate-700 mx-auto mb-3" />
                                    <p className="text-slate-500">No payment methods found</p>
                                    <Link href="/admin/payment-methods/create">
                                        <Button className="mt-4" variant="outline">
                                            <Plus className="h-4 w-4 mr-2" />
                                            Add First Payment Method
                                        </Button>
                                    </Link>
                                </div>
                            )}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    );
}



