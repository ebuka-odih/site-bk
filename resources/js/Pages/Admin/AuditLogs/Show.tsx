import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Head, Link } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import { AuditLog, PageProps } from '@/types';

export default function Show({ auditLog }: PageProps & { auditLog: AuditLog }) {
    return (
        <AdminLayout>
            <Head title="Audit Log Details" />
            
            <div className="space-y-6">
                <div>
                    <Link href="/admin/activity-logs" className="inline-flex items-center text-sm text-slate-400 hover:text-slate-50 mb-4">
                        <ArrowLeft className="h-4 w-4 mr-1" />
                        Back to Activity Logs
                    </Link>
                    <h1 className="text-3xl font-bold text-slate-50">Activity Log Details</h1>
                    <p className="text-slate-400 mt-1">Event: {auditLog.event}</p>
                </div>

                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50">Log Information</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div>
                            <p className="text-sm text-slate-400">Event</p>
                            <p className="text-slate-50 font-medium">{auditLog.event}</p>
                        </div>
                        <div>
                            <p className="text-sm text-slate-400">Actor</p>
                            <p className="text-slate-50 font-medium">{auditLog.actor?.name || 'System'}</p>
                        </div>
                        {auditLog.ip_address && (
                            <div>
                                <p className="text-sm text-slate-400">IP Address</p>
                                <p className="text-slate-50 font-mono">{auditLog.ip_address}</p>
                            </div>
                        )}
                        {auditLog.user_agent && (
                            <div>
                                <p className="text-sm text-slate-400">User Agent</p>
                                <p className="text-slate-50 text-sm">{auditLog.user_agent}</p>
                            </div>
                        )}
                        <div>
                            <p className="text-sm text-slate-400">Timestamp</p>
                            <p className="text-slate-50">{new Date(auditLog.created_at).toLocaleString()}</p>
                        </div>
                        {auditLog.details && Object.keys(auditLog.details).length > 0 && (
                            <div>
                                <p className="text-sm text-slate-400 mb-2">Details</p>
                                <pre className="bg-slate-800 rounded-lg p-4 text-xs text-slate-300 overflow-x-auto">
                                    {JSON.stringify(auditLog.details, null, 2)}
                                </pre>
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    );
}


