import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Head, Link } from '@inertiajs/react';
import { AuditLog, PageProps } from '@/types';
import { Activity, Download } from 'lucide-react';
import { Button } from '@/Components/ui/button';

export default function Index({ logs, events }: PageProps & { logs: { data: AuditLog[] }; events: string[] }) {
    return (
        <AdminLayout>
            <Head title="Activity Logs" />
            
            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-3xl font-bold text-slate-50">Activity Logs</h1>
                        <p className="text-slate-400 mt-1">Security audit trail and system events</p>
                    </div>
                    <a href="/admin/activity-logs/export">
                        <Button className="bg-slate-700 hover:bg-slate-600">
                            <Download className="h-4 w-4 mr-2" />
                            Export Logs
                        </Button>
                    </a>
                </div>

                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50">Recent Activity</CardTitle>
                        <CardDescription className="text-slate-400">
                            Latest system events and admin actions
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="space-y-4">
                            {logs.data.map((log) => (
                                <div key={log.id} className="flex items-start space-x-4 p-4 bg-slate-800 rounded-lg border border-slate-700 hover:border-slate-600">
                                    <div className="flex-shrink-0">
                                        <div className="h-10 w-10 rounded-full bg-slate-900 flex items-center justify-center">
                                            <Activity className="h-5 w-5 text-slate-400" />
                                        </div>
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <p className="text-sm font-medium text-slate-50">{log.event}</p>
                                        <p className="text-xs text-slate-400 mt-1">
                                            By {log.actor?.name || 'System'} • {new Date(log.created_at).toLocaleString()}
                                        </p>
                                        {log.ip_address && (
                                            <p className="text-xs text-slate-500 mt-1">IP: {log.ip_address}</p>
                                        )}
                                    </div>
                                    <Link
                                        href={`/admin/activity-logs/${log.id}`}
                                        className="text-sm text-slate-400 hover:text-slate-50"
                                    >
                                        View
                                    </Link>
                                </div>
                            ))}

                            {logs.data.length === 0 && (
                                <div className="text-center py-12">
                                    <Activity className="h-12 w-12 text-slate-700 mx-auto mb-3" />
                                    <p className="text-slate-500">No activity logs found</p>
                                </div>
                            )}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    );
}


