<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Tourist;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class TouristController extends Controller
{
    public function index(Request $request, Response $response): Response
    {
        $params = $request->getParsedBody();
        $Builder = Tourist::query();
        if (isset($params['name']) && $params['name']) {
            $Builder->where('name', $params['name']);
        }
        return $this->json($response, $Builder->get()->toArray());
        return $response;
    }

    public function get(Request $request, Response $response, $args): Response
    {
        // return $this->render($response, 'home/index.twig');
        return $this->json($response, Tourist::find($args['id']));
        return $response;
    }

    public function create(Request $request, Response $response, $args): Response
    {
        $params = $request->getQueryParams();
        $raw = $request->getParsedBody();
        if (!$raw['formId']) return $this->json($response, ['msg' => 'No form request!']);

        $answers = new Collection($raw['answerContents']);
        $data = [];
        $data['creatorName'] = $raw['creatorName'];
        $data['first_name'] = $answers->firstWhere('title', '姓氏')['value'] ?? '';
        $data['last_name'] = $answers->firstWhere('title', '名字')['value'] ?? '';
        $data['name'] = $data['first_name'] . $data['last_name'];
        $data['start_date'] = $answers->firstWhere('title', '预定在日本逗留的日期')['value'] ?? date('Y-m-d');
        $stayDays = $answers->firstWhere('title', '预定在日本逗留天数')['value'] ?? 6;
        $data['end_date'] = date('Y-m-d', strtotime($data['start_date'] . ' + ' . $stayDays . ' days'));
        $data['idcard'] = $answers->firstWhere('title', '请上传身份证照片')['value'][5] ?? $answers->firstWhere('title', '身份证号')['value'][0] ?? '';
        $data['sex'] = $data['idcard'] ? (substr($data['idcard'], 16, 1) % 2 ? '男' : '女') : ($answers->firstWhere('title', '性别')['value'][0] ?? '');
        $data['birthday'] = $data['idcard'] ? substr($data['idcard'], 6, 4) . '-' . substr($data['idcard'], 10, 2) . '-' . substr($data['idcard'], 12, 2) : ($answers->firstWhere('title', '出生日期')['value'] ?? '');
        $data['phone'] = $answers->firstWhere('title', '手机号')['value'] ?? '';
        $data['passport'] = $answers->firstWhere('title', '请上传护照照片')['value'][2] ?? $answers->firstWhere('title', '护照号')['value'][0] ?? '';
        $passportTypeEn = $answers->firstWhere('title', '请上传护照照片')['value'][1][0] ?? '';
        $passportType = $passportTypeEn == 'P' ? '普通' : ($passportTypeEn == 'W' ? ' 外交' : ($passportTypeEn == 'G' ? ' 公务' : '其他'));
        $data['passport_type'] = $passportTypeEn ? $passportType : ($answers->firstWhere('title', '护照号')['value'][0] ?? '');
        $data['passport_province'] = $answers->firstWhere('title', '请上传护照照片')['value'][5] ?? $answers->firstWhere('title', '护照签发地')['value'][0] ?? '';
        $data['passport_org'] = $answers->firstWhere('title', '请上传护照照片')['value'][6] ?? $answers->firstWhere('title', '护照签发机关')['value'][0] ?? '';
        $data['passport_issue'] = $answers->firstWhere('title', '请上传护照照片')['value'][3] ?? $answers->firstWhere('title', '护照签发日期')['value'][0] ?? '';
        $data['passport_expire'] = $answers->firstWhere('title', '请上传护照照片')['value'][4] ?? $answers->firstWhere('title', '护照有效期')['value'][0] ?? '';
        $data['raw'] = json_encode($raw);

        $tourist = Tourist::updateOrCreate([
            'aid' => $raw['aid']
        ], $data);
        return $this->json($response, ['id' => $tourist->id]);
    }
    public function create_by_wps(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();
        if (!$body) {
            return $this->json($response, ['bind_code' => '20240420223823449467314']);
        }
        return $this->create($request, $response, $args);
    }
}
