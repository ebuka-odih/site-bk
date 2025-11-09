import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, AlertTriangle } from 'lucide-react';
import { PageProps } from '@/types';

export default function Fraud({ failedTransactions, highVolumeUsers }: PageProps & { failedTransactions: any; highVolumeUsers: any }) {
    return (
        <AdminLayout>
            <Head title="Fraud Detection" />
            
            <div className="space-y-6">
                <div>
                    <Link href="/admin/reports" className="inline-flex items-center text-sm text-slate-400 hover:text-slate-50 mb-4">
                        <ArrowLeft className="h-4 w-4 mr-1" />
                        Back to Reports
                    </Link>
                    <h1 className="text-3xl font-bold text-slate-50">Fraud Detection</h1>
                    <p className="text-slate-400 mt-1">Suspicious activity and high-risk patterns</p>
                </div>

                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50">Suspicious Activities</CardTitle>
                        <CardDescription className="text-slate-400">
                            Accounts with unusual patterns
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="text-center py-12">
                            <AlertTriangle className="h-12 w-12 text-slate-700 mx-auto mb-3" />
                            <p className="text-slate-500">No suspicious activity detected</p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    );
}


