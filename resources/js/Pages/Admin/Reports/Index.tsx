import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Head } from '@inertiajs/react';
import { TrendingUp, Users, ArrowLeftRight, Ticket, Activity } from 'lucide-react';
import { PageProps } from '@/types';

export default function Index({ 
    transactionStats, 
    userStats, 
    codeStats 
}: PageProps & { transactionStats: any; userStats: any; codeStats: any }) {
    return (
        <AdminLayout>
            <Head title="Reports" />
            
            <div className="space-y-6">
                <div>
                    <h1 className="text-3xl font-bold text-slate-50">Reports & Analytics</h1>
                    <p className="text-slate-400 mt-1">Performance metrics and insights</p>
                </div>

                {/* Transaction Stats */}
                <div>
                    <h2 className="text-xl font-semibold text-slate-50 mb-4">Transaction Overview</h2>
                    <div className="grid gap-4 md:grid-cols-5">
                        <Card className="bg-slate-900 border-slate-800">
                            <CardHeader className="pb-2">
                                <CardDescription className="text-slate-400">Total</CardDescription>
                                <CardTitle className="text-2xl text-slate-50">{transactionStats.total}</CardTitle>
                            </CardHeader>
                        </Card>
                        <Card className="bg-slate-900 border-slate-800">
                            <CardHeader className="pb-2">
                                <CardDescription className="text-slate-400">Completed</CardDescription>
                                <CardTitle className="text-2xl text-emerald-400">{transactionStats.completed}</CardTitle>
                            </CardHeader>
                        </Card>
                        <Card className="bg-slate-900 border-slate-800">
                            <CardHeader className="pb-2">
                                <CardDescription className="text-slate-400">Pending</CardDescription>
                                <CardTitle className="text-2xl text-amber-400">{transactionStats.pending}</CardTitle>
                            </CardHeader>
                        </Card>
                        <Card className="bg-slate-900 border-slate-800">
                            <CardHeader className="pb-2">
                                <CardDescription className="text-slate-400">Failed</CardDescription>
                                <CardTitle className="text-2xl text-red-400">{transactionStats.failed}</CardTitle>
                            </CardHeader>
                        </Card>
                        <Card className="bg-slate-900 border-slate-800">
                            <CardHeader className="pb-2">
                                <CardDescription className="text-slate-400">Total Value</CardDescription>
                                <CardTitle className="text-2xl text-slate-50">
                                    ${transactionStats.total_value?.toLocaleString('en-US', { minimumFractionDigits: 2 })}
                                </CardTitle>
                            </CardHeader>
                        </Card>
                    </div>
                </div>

                {/* User Stats */}
                <div>
                    <h2 className="text-xl font-semibold text-slate-50 mb-4">User Statistics</h2>
                    <div className="grid gap-4 md:grid-cols-5">
                        <Card className="bg-slate-900 border-slate-800">
                            <CardHeader className="pb-2">
                                <CardDescription className="text-slate-400">Total Users</CardDescription>
                                <CardTitle className="text-2xl text-slate-50">{userStats.total}</CardTitle>
                            </CardHeader>
                        </Card>
                        <Card className="bg-slate-900 border-slate-800">
                            <CardHeader className="pb-2">
                                <CardDescription className="text-slate-400">Active</CardDescription>
                                <CardTitle className="text-2xl text-emerald-400">{userStats.active}</CardTitle>
                            </CardHeader>
                        </Card>
                        <Card className="bg-slate-900 border-slate-800">
                            <CardHeader className="pb-2">
                                <CardDescription className="text-slate-400">Suspended</CardDescription>
                                <CardTitle className="text-2xl text-amber-400">{userStats.suspended}</CardTitle>
                            </CardHeader>
                        </Card>
                        <Card className="bg-slate-900 border-slate-800">
                            <CardHeader className="pb-2">
                                <CardDescription className="text-slate-400">Locked</CardDescription>
                                <CardTitle className="text-2xl text-red-400">{userStats.locked}</CardTitle>
                            </CardHeader>
                        </Card>
                        <Card className="bg-slate-900 border-slate-800">
                            <CardHeader className="pb-2">
                                <CardDescription className="text-slate-400">New This Month</CardDescription>
                                <CardTitle className="text-2xl text-slate-50">{userStats.new_this_month}</CardTitle>
                            </CardHeader>
                        </Card>
                    </div>
                </div>

                {/* Code Stats */}
                <div>
                    <h2 className="text-xl font-semibold text-slate-50 mb-4">Transaction Codes</h2>
                    <div className="grid gap-4 md:grid-cols-4">
                        <Card className="bg-slate-900 border-slate-800">
                            <CardHeader className="pb-2">
                                <CardDescription className="text-slate-400">Total Codes</CardDescription>
                                <CardTitle className="text-2xl text-slate-50">{codeStats.total}</CardTitle>
                            </CardHeader>
                        </Card>
                        <Card className="bg-slate-900 border-slate-800">
                            <CardHeader className="pb-2">
                                <CardDescription className="text-slate-400">Active</CardDescription>
                                <CardTitle className="text-2xl text-emerald-400">{codeStats.active}</CardTitle>
                            </CardHeader>
                        </Card>
                        <Card className="bg-slate-900 border-slate-800">
                            <CardHeader className="pb-2">
                                <CardDescription className="text-slate-400">Used</CardDescription>
                                <CardTitle className="text-2xl text-slate-400">{codeStats.used}</CardTitle>
                            </CardHeader>
                        </Card>
                        <Card className="bg-slate-900 border-slate-800">
                            <CardHeader className="pb-2">
                                <CardDescription className="text-slate-400">Expired</CardDescription>
                                <CardTitle className="text-2xl text-red-400">{codeStats.expired}</CardTitle>
                            </CardHeader>
                        </Card>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}


