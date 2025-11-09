import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Head, Link, router } from '@inertiajs/react';
import { TransactionCode, PageProps } from '@/types';
import { Plus, Ticket } from 'lucide-react';

type Filters = {
    status: string;
    type?: string | null;
};

const statusFilters: Array<{ value: Filters['status']; label: string }> = [
    { value: 'active', label: 'Active' },
    { value: 'all', label: 'All' },
    { value: 'used', label: 'Used' },
    { value: 'expired', label: 'Expired' },
];

export default function Index({
    codes,
    filters,
}: PageProps & { codes: { data: TransactionCode[] }; filters: Filters }) {
    const activeStatus = filters?.status ?? 'active';

    const formatAmount = (amount?: number | string | null) => {
        if (amount === null || amount === undefined) {
            return 'N/A';
        }

        const numeric = typeof amount === 'string' ? parseFloat(amount) : amount;

        if (!Number.isFinite(numeric)) {
            return 'N/A';
        }

        return `$${numeric.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
    };

    const handleStatusChange = (value: Filters['status']) => {
        if (value === activeStatus) {
            return;
        }

        const query: Record<string, string> = { status: value };

        if (filters?.type) {
            query.type = filters.type;
        }

        router.get('/admin/codes', query, {
            preserveScroll: true,
            preserveState: true,
        });
    };
    const getStatusBadge = (code: TransactionCode) => {
        if (code.is_used) {
            return <Badge className="bg-slate-800 text-slate-300 border-slate-700">Used</Badge>;
        }
        const isExpired = new Date(code.expires_at) < new Date();
        if (isExpired) {
            return <Badge className="bg-red-900/50 text-red-200 border-red-700">Expired</Badge>;
        }
        return <Badge className="bg-emerald-900/50 text-emerald-200 border-emerald-700">Active</Badge>;
    };

    return (
        <AdminLayout>
            <Head title="Transaction Codes" />
            
            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-3xl font-bold text-slate-50">Transaction Codes</h1>
                        <p className="text-slate-400 mt-1">Manage authorization codes for deposits and withdrawals</p>
                    </div>
                    <Link href="/admin/codes/create">
                        <Button className="bg-amber-600 hover:bg-amber-700 text-white shadow-lg shadow-amber-900/50 hover:shadow-xl hover:shadow-amber-900/60 transition-all">
                            <Plus className="h-4 w-4 mr-2" />
                            Generate Code
                        </Button>
                    </Link>
                </div>

                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50">All Codes</CardTitle>
                        <CardDescription className="text-slate-400">
                            Authorization codes for transaction approval. Showing {activeStatus} codes.
                        </CardDescription>
                        <div className="mt-4 flex flex-wrap items-center gap-2">
                            {statusFilters.map((filter) => (
                                <Button
                                    key={filter.value}
                                    size="sm"
                                    variant={filter.value === activeStatus ? 'default' : 'outline'}
                                    className={
                                        filter.value === activeStatus
                                            ? 'bg-amber-600 hover:bg-amber-700 text-white'
                                            : 'border-slate-700 text-slate-300 hover:bg-slate-800/60'
                                    }
                                    onClick={() => handleStatusChange(filter.value)}
                                >
                                    {filter.label}
                                </Button>
                            ))}
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead>
                                    <tr className="border-b border-slate-800">
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Code</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Type</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Amount</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Status</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Expires</th>
                                        <th className="text-right py-3 px-4 text-sm font-medium text-slate-400">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {codes.data.map((code) => (
                                        <tr key={code.id} className="border-b border-slate-800 hover:bg-slate-800/50">
                                            <td className="py-3 px-4">
                                                <span className="text-sm font-mono font-semibold text-slate-50">{code.code}</span>
                                            </td>
                                            <td className="py-3 px-4">
                                                <Badge className={
                                                    code.type === 'deposit' ? 'bg-green-900/50 text-green-200 border-green-700' :
                                                    code.type === 'withdrawal' ? 'bg-red-900/50 text-red-200 border-red-700' :
                                                    'bg-blue-900/50 text-blue-200 border-blue-700'
                                                }>
                                                    {code.type}
                                                </Badge>
                                            </td>
                                            <td className="py-3 px-4 text-sm text-slate-50">
                                                {formatAmount(code.amount)}
                                            </td>
                                            <td className="py-3 px-4">
                                                {getStatusBadge(code)}
                                            </td>
                                            <td className="py-3 px-4 text-sm text-slate-400">
                                                {new Date(code.expires_at).toLocaleDateString()}
                                            </td>
                                            <td className="py-3 px-4 text-right">
                                                <Link
                                                    href={`/admin/codes/${code.id}`}
                                                    className="text-sm text-slate-400 hover:text-slate-50"
                                                >
                                                    View
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>

                            {codes.data.length === 0 && (
                                <div className="text-center py-12">
                                    <Ticket className="h-12 w-12 text-slate-700 mx-auto mb-3" />
                                    <p className="text-slate-500">
                                        No transaction codes found for the selected filter.
                                    </p>
                                    {activeStatus !== 'all' && (
                                        <Button
                                            onClick={() => handleStatusChange('all')}
                                            className="mt-4 border border-slate-700 bg-slate-900 text-slate-200 hover:bg-slate-800"
                                        >
                                            View all codes
                                        </Button>
                                    )}
                                    <Link href="/admin/codes/create">
                                        <Button className="mt-4 bg-blue-600 hover:bg-blue-700 text-white">
                                            Generate Your First Code
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


