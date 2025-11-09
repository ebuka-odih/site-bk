import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Link } from '@inertiajs/react';
import {
    Users,
    ArrowLeftRight,
    DollarSign,
    Activity,
    TrendingUp,
    Plus,
    ArrowUpRight,
} from 'lucide-react';
import { PageProps, Transaction, User } from '@/types';

interface DashboardProps extends PageProps {
    stats: {
        total_users: number;
        total_transactions: number;
        total_balance: number;
        recent_activity: Array<{
            description: string;
            amount: number;
            date: string;
            status: string;
        }>;
    };
}

export default function Dashboard({ stats }: DashboardProps) {
    return (
        <AdminLayout>
            <div className="space-y-6">
                {/* Header */}
                <div>
                    <h1 className="text-3xl font-bold text-slate-50">Dashboard</h1>
                    <p className="text-slate-400 mt-1">Overview of your banking system</p>
                </div>

                {/* Stats Grid */}
                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    {/* Total Users */}
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium text-slate-400">
                                Total Users
                            </CardTitle>
                            <Users className="h-4 w-4 text-slate-500" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-slate-50">{stats.total_users}</div>
                            <p className="text-xs text-slate-500 flex items-center mt-1">
                                <TrendingUp className="h-3 w-3 mr-1 text-emerald-400" />
                                +12% from last month
                            </p>
                        </CardContent>
                    </Card>

                    {/* Transactions */}
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium text-slate-400">
                                Transactions
                            </CardTitle>
                            <ArrowLeftRight className="h-4 w-4 text-slate-500" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-slate-50">{stats.total_transactions}</div>
                            <p className="text-xs text-slate-500 flex items-center mt-1">
                                <TrendingUp className="h-3 w-3 mr-1 text-emerald-400" />
                                +8% from last month
                            </p>
                        </CardContent>
                    </Card>

                    {/* Total Balance */}
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium text-slate-400">
                                Total Balance
                            </CardTitle>
                            <DollarSign className="h-4 w-4 text-slate-500" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-slate-50">
                                ${stats.total_balance.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                            </div>
                            <p className="text-xs text-slate-500 flex items-center mt-1">
                                <TrendingUp className="h-3 w-3 mr-1 text-emerald-400" />
                                +15% from last month
                            </p>
                        </CardContent>
                    </Card>

                    {/* Active Sessions */}
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium text-slate-400">
                                Active Sessions
                            </CardTitle>
                            <Activity className="h-4 w-4 text-slate-500" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-slate-50">24</div>
                            <p className="text-xs text-slate-500 flex items-center mt-1">
                                <TrendingUp className="h-3 w-3 mr-1 text-emerald-400" />
                                +3 from yesterday
                            </p>
                        </CardContent>
                    </Card>
                </div>

                {/* Charts and Tables */}
                <div className="grid gap-4 md:grid-cols-2">
                    {/* Recent Transactions */}
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <div className="flex items-center justify-between">
                                <div>
                                    <CardTitle className="text-slate-50">Recent Transactions</CardTitle>
                                    <CardDescription className="text-slate-400">Latest transaction activity</CardDescription>
                                </div>
                                <Link href="/admin/transactions">
                                    <Button variant="ghost" size="sm" className="text-slate-400 hover:text-slate-50">
                                        View all
                                        <ArrowUpRight className="ml-1 h-4 w-4" />
                                    </Button>
                                </Link>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-4">
                                {stats.recent_activity.map((transaction, index) => (
                                    <div key={index} className="flex items-center justify-between border-b border-slate-800 last:border-0 pb-3 last:pb-0">
                                        <div className="flex items-center space-x-3">
                                            <div className="h-9 w-9 rounded-full bg-slate-800 flex items-center justify-center">
                                                <DollarSign className="h-4 w-4 text-slate-400" />
                                            </div>
                                            <div>
                                                <p className="text-sm font-medium text-slate-50">{transaction.description || 'Transaction'}</p>
                                                <p className="text-xs text-slate-500">{transaction.date || 'Today'}</p>
                                            </div>
                                        </div>
                                        <div className="text-right">
                                            <p className="text-sm font-semibold text-slate-50">
                                                ${transaction.amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}
                                            </p>
                                            <Badge variant={transaction.status === 'Completed' ? 'secondary' : 'outline'} className="text-xs bg-slate-800 text-slate-300 border-slate-700">
                                                {transaction.status || 'Completed'}
                                            </Badge>
                                        </div>
                                    </div>
                                ))}
                                {stats.recent_activity.length === 0 && (
                                    <div className="text-center py-8">
                                        <ArrowLeftRight className="h-12 w-12 text-slate-700 mx-auto mb-3" />
                                        <p className="text-sm text-slate-500">No recent transactions</p>
                                    </div>
                                )}
                            </div>
                        </CardContent>
                    </Card>

                    {/* Quick Actions */}
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Quick Actions</CardTitle>
                            <CardDescription className="text-slate-400">Common administrative tasks</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="grid grid-cols-2 gap-3">
                                <Link href="/admin/users/create">
                                    <Button variant="outline" className="w-full justify-start h-auto py-4 bg-slate-800 border-slate-700 hover:bg-slate-700 hover:border-slate-600">
                                        <div className="flex flex-col items-start w-full">
                                            <div className="flex items-center gap-2 mb-1">
                                                <Users className="h-4 w-4 text-slate-400" />
                                                <span className="font-medium text-slate-50">Add User</span>
                                            </div>
                                            <span className="text-xs text-slate-500">Create new account</span>
                                        </div>
                                    </Button>
                                </Link>

                                <Link href="/admin/transactions/create">
                                    <Button variant="outline" className="w-full justify-start h-auto py-4 bg-slate-800 border-slate-700 hover:bg-slate-700 hover:border-slate-600">
                                        <div className="flex flex-col items-start w-full">
                                            <div className="flex items-center gap-2 mb-1">
                                                <Plus className="h-4 w-4 text-slate-400" />
                                                <span className="font-medium text-slate-50">New Transaction</span>
                                            </div>
                                            <span className="text-xs text-slate-500">Process payment</span>
                                        </div>
                                    </Button>
                                </Link>

                                <Link href="/admin/codes/create">
                                    <Button variant="outline" className="w-full justify-start h-auto py-4 bg-blue-600 border-blue-500 hover:bg-blue-700 hover:border-blue-600">
                                        <div className="flex flex-col items-start w-full">
                                            <div className="flex items-center gap-2 mb-1">
                                                <Activity className="h-4 w-4 text-white" />
                                                <span className="font-medium text-white">Generate Code</span>
                                            </div>
                                            <span className="text-xs text-blue-100">Authorization code</span>
                                        </div>
                                    </Button>
                                </Link>

                                <Link href="/admin/reports">
                                    <Button variant="outline" className="w-full justify-start h-auto py-4 bg-slate-800 border-slate-700 hover:bg-slate-700 hover:border-slate-600">
                                        <div className="flex flex-col items-start w-full">
                                            <div className="flex items-center gap-2 mb-1">
                                                <ArrowLeftRight className="h-4 w-4 text-slate-400" />
                                                <span className="font-medium text-slate-50">View Reports</span>
                                            </div>
                                            <span className="text-xs text-slate-500">Analytics</span>
                                        </div>
                                    </Button>
                                </Link>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* System Status */}
                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50">System Status</CardTitle>
                        <CardDescription className="text-slate-400">Current system health and services</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div className="flex items-center space-x-3">
                                <div className="h-2 w-2 rounded-full bg-emerald-400"></div>
                                <div>
                                    <p className="text-sm font-medium text-slate-50">Database</p>
                                    <p className="text-xs text-slate-500">Connected</p>
                                </div>
                            </div>
                            <div className="flex items-center space-x-3">
                                <div className="h-2 w-2 rounded-full bg-emerald-400"></div>
                                <div>
                                    <p className="text-sm font-medium text-slate-50">API Services</p>
                                    <p className="text-xs text-slate-500">Operational</p>
                                </div>
                            </div>
                            <div className="flex items-center space-x-3">
                                <div className="h-2 w-2 rounded-full bg-amber-400"></div>
                                <div>
                                    <p className="text-sm font-medium text-slate-50">Backup</p>
                                    <p className="text-xs text-slate-500">In Progress</p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    );
}

