import { Card, CardContent } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import MobileLayout from '@/Layouts/MobileLayout';
import { PageProps } from '@/types';
import { router } from '@inertiajs/react';
import { useState } from 'react';
import axios from 'axios';

interface Transaction {
    id: number;
    reference: string;
    type: string;
    amount: number;
    fee: number;
    status: string;
    description: string;
    created_at: string;
    recipient?: {
        name: string;
        account_number: string;
    };
    metadata?: {
        transfer_type?: string;
        beneficiary_name?: string;
        bank_name?: string;
        account_number?: string;
    };
}

interface TransferSuccessProps extends PageProps {
    transaction: Transaction;
}

export default function TransferSuccess({ auth, transaction }: TransferSuccessProps) {
    const [downloadingPdf, setDownloadingPdf] = useState(false);
    const transferType = transaction.metadata?.transfer_type === 'wire' ? 'wire' : 'internal';
    const isWireTransfer = transferType === 'wire';
    const isPending = transaction.status === 'pending';

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2,
        }).format(amount / 100);
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    };

    const handleDownloadPdf = async () => {
        setDownloadingPdf(true);
        try {
            const response = await axios.get(`/transfer/receipt/${transaction.id}`, {
                responseType: 'blob',
            });

            // Create a blob URL and trigger download
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', `transaction-${transaction.reference}.pdf`);
            document.body.appendChild(link);
            link.click();
            link.remove();
            window.URL.revokeObjectURL(url);
        } catch (error) {
            console.error('Failed to download PDF:', error);
            alert('Failed to download receipt. Please try again.');
        } finally {
            setDownloadingPdf(false);
        }
    };

    const handleViewTransactions = () => {
        router.visit('/transactions');
    };

    const handleNewTransfer = () => {
        router.visit('/transfer');
    };

    const handleBackToDashboard = () => {
        router.visit('/dashboard');
    };

    const statusLabel = (() => {
        const normalizedStatus = transaction.status.toLowerCase();
        if (normalizedStatus === 'pending' && isWireTransfer) {
            return 'Pending Approval';
        }
        return normalizedStatus.charAt(0).toUpperCase() + normalizedStatus.slice(1);
    })();

    const statusBadgeColor =
        transaction.status === 'completed'
            ? 'bg-green-900/30 text-green-300 border-green-800'
            : transaction.status === 'failed'
                ? 'bg-red-900/30 text-red-300 border-red-800'
                : 'bg-amber-900/30 text-amber-200 border-amber-700';

    const statusDotColor =
        transaction.status === 'completed'
            ? 'bg-green-400'
            : transaction.status === 'failed'
                ? 'bg-red-400'
                : 'bg-amber-300';

    const heroTitle = isWireTransfer && isPending ? 'Wire Transfer Initiated' : 'Transfer Successful!';
    const heroSubtitle =
        isWireTransfer && isPending
            ? 'Your wire transfer request has been submitted and is awaiting approval.'
            : 'Your transfer has been completed.';

    return (
        <MobileLayout user={auth.user} title="Transfer Successful" currentRoute="">
            <div className="px-4 py-6 space-y-6 max-w-2xl mx-auto">
                {/* Success Animation Card */}
                <Card className="bg-gradient-to-br from-green-600 to-emerald-600 border-0 text-white shadow-xl overflow-hidden relative">
                    <div className="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
                    <div className="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-24 -mb-24"></div>
                    <CardContent className="pt-8 pb-8 relative z-10">
                        <div className="text-center space-y-4">
                            {/* Success Icon */}
                            <div className="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm mb-2">
                                <svg 
                                    className="w-12 h-12 text-white animate-bounce" 
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                    style={{ animationDuration: '1s', animationIterationCount: '2' }}
                                >
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h2 className="text-2xl font-bold mb-2">{heroTitle}</h2>
                                <p className="text-green-100">{heroSubtitle}</p>
                            </div>
                            <div className="pt-4">
                                <p className="text-green-100 text-sm mb-1">Amount Sent</p>
                                <p className="text-4xl font-bold">{formatCurrency(transaction.amount)}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Transaction Details Card */}
                <Card className="bg-slate-900 border-slate-800">
                    <CardContent className="pt-6 space-y-4">
                        <div className="border-b border-slate-800 pb-4">
                            <h3 className="text-lg font-semibold text-slate-50 mb-4">Transaction Details</h3>
                            <div className="space-y-3">
                                <div className="flex justify-between">
                                    <span className="text-slate-400">Reference Number</span>
                                    <span className="text-slate-50 font-mono text-sm">{transaction.reference}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-slate-400">Date & Time</span>
                                    <span className="text-slate-50 text-sm">{formatDate(transaction.created_at)}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-slate-400">Status</span>
                                    <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ${statusBadgeColor}`}>
                                        <span className={`w-1.5 h-1.5 rounded-full mr-1.5 ${statusDotColor}`}></span>
                                        {statusLabel}
                                    </span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-slate-400">Transfer Type</span>
                                    <span className="text-slate-50 text-sm">
                                        {isWireTransfer ? 'Wire Transfer' : 'Internal Transfer'}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div className="border-b border-slate-800 pb-4">
                            <h4 className="text-sm font-semibold text-slate-300 mb-3">Recipient Information</h4>
                            <div className="space-y-3">
                                {isWireTransfer ? (
                                    <>
                                        <div className="flex justify-between">
                                            <span className="text-slate-400">Beneficiary</span>
                                            <span className="text-slate-50 font-medium">{transaction.metadata?.beneficiary_name || 'N/A'}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-slate-400">Bank</span>
                                            <span className="text-slate-50">{transaction.metadata?.bank_name || 'N/A'}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-slate-400">Account</span>
                                            <span className="text-slate-50 font-mono text-sm">{transaction.metadata?.account_number || 'N/A'}</span>
                                        </div>
                                    </>
                                ) : (
                                    <>
                                        <div className="flex justify-between">
                                            <span className="text-slate-400">Name</span>
                                            <span className="text-slate-50 font-medium">{transaction.recipient?.name || 'N/A'}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-slate-400">Account Number</span>
                                            <span className="text-slate-50 font-mono">{transaction.recipient?.account_number || 'N/A'}</span>
                                        </div>
                                    </>
                                )}
                            </div>
                        </div>

                        <div>
                            <h4 className="text-sm font-semibold text-slate-300 mb-3">Amount Breakdown</h4>
                            <div className="space-y-3">
                                <div className="flex justify-between">
                                    <span className="text-slate-400">Transfer Amount</span>
                                    <span className="text-slate-50">{formatCurrency(transaction.amount)}</span>
                                </div>
                                {transaction.fee > 0 && (
                                    <div className="flex justify-between">
                                        <span className="text-slate-400">Transaction Fee</span>
                                        <span className="text-slate-50">{formatCurrency(transaction.fee)}</span>
                                    </div>
                                )}
                                <div className="flex justify-between pt-2 border-t border-slate-800">
                                    <span className="text-slate-50 font-semibold">Total</span>
                                    <span className="text-slate-50 font-bold text-lg">{formatCurrency(transaction.amount + transaction.fee)}</span>
                                </div>
                            </div>
                        </div>

                        {transaction.description && (
                            <div className="pt-2">
                                <h4 className="text-sm font-semibold text-slate-300 mb-2">Description</h4>
                                <p className="text-slate-400 text-sm">{transaction.description}</p>
                            </div>
                        )}
                    </CardContent>
                </Card>

                {/* Action Buttons */}
                <div className="space-y-3">
                    <Button
                        onClick={handleDownloadPdf}
                        disabled={downloadingPdf}
                        className="w-full bg-blue-600 hover:bg-blue-700 text-white py-6 text-base flex items-center justify-center space-x-2"
                    >
                        {downloadingPdf ? (
                            <>
                                <svg className="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Generating PDF...</span>
                            </>
                        ) : (
                            <>
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Download Receipt (PDF)</span>
                            </>
                        )}
                    </Button>

                    <div className="grid grid-cols-2 gap-3">
                        <Button
                            onClick={handleNewTransfer}
                            variant="outline"
                            className="border-slate-700 text-slate-300 hover:bg-slate-800 py-6"
                        >
                            New Transfer
                        </Button>
                        <Button
                            onClick={handleViewTransactions}
                            variant="outline"
                            className="border-slate-700 text-slate-300 hover:bg-slate-800 py-6"
                        >
                            View History
                        </Button>
                    </div>

                    <Button
                        onClick={handleBackToDashboard}
                        variant="ghost"
                        className="w-full text-slate-400 hover:text-slate-50 hover:bg-slate-800 py-6"
                    >
                        Back to Dashboard
                    </Button>
                </div>

                {/* Info Notice */}
                <Card className="bg-blue-950/20 border-blue-900">
                    <CardContent className="pt-6">
                        <div className="flex items-start space-x-3">
                            <svg className="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div className="text-sm text-blue-200 space-y-1">
                                {isWireTransfer ? (
                                    <>
                                        <p className="font-medium">Wire transfer initiated!</p>
                                        <p>Your wire transfer request has been submitted and is pending admin approval. This typically takes 1-3 business days.</p>
                                    </>
                                ) : (
                                    <>
                                        <p className="font-medium">Transfer completed successfully!</p>
                                        <p>The funds have been instantly transferred to the recipient's account.</p>
                                    </>
                                )}
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </MobileLayout>
    );
}


