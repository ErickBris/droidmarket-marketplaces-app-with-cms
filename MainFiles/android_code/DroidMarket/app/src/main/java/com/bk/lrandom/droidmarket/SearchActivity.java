
package com.bk.lrandom.droidmarket;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.util.ArrayList;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONArray;
import org.json.JSONObject;

import android.annotation.SuppressLint;
import android.app.SearchManager;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AbsListView;
import android.widget.AbsListView.OnScrollListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.ProgressBar;

import com.bk.lrandom.droidmarket.adapters.ProductsAdapter;
import com.bk.lrandom.droidmarket.business.JSONFetchTask;
import com.bk.lrandom.droidmarket.confs.constants;
import com.bk.lrandom.droidmarket.models.Products;

@SuppressLint("NewApi")
public class SearchActivity extends ActionBarParentActivity {
    ArrayList<Products> products_list = new ArrayList<Products>();
    ListView list;
    ProductsAdapter adapter;
    String TAG = "ProductsActivity";
    JSONFetchTask jsonFetchTask;
    static InputStream is = null;
    static JSONObject jObj = null;
    static String jsonString = "";
    String query = null, tmpQuery = null;
    int COUNT_ITEM_LOAD_MORE = 5;
    int first = 0, pastVisiblesItems, visibleItemCount, totalItemCount;
    boolean loadingMore = true;
    SwipeRefreshLayout swipeRefreshLayout;
    RecyclerView rv;
    Button btnAll, btnSell, btnBuy;
    LinearLayout loadMorePrg;
    Toolbar toolbar;

    @SuppressWarnings("deprecation")
    private void setButtonFocus(Button btn, int drawable) {
        if (android.os.Build.VERSION.SDK_INT < android.os.Build.VERSION_CODES.JELLY_BEAN) {
            btn.setBackgroundDrawable(getResources().getDrawable(drawable));
        } else {
            btn.setBackground(getResources().getDrawable(drawable));
        }
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        // TODO Auto-generated method stub
        super.onCreate(savedInstanceState);
        setContentView(R.layout.search_endless_container_layout);
        setTitle(getResources().getString(R.string.result_label));
        toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        getSupportActionBar().setHomeButtonEnabled(true);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        query = getResources().getString(R.string.products_json_url)
                + "products?x=trick";
        handleIntent(getIntent());
    }

    @Override
    public boolean onPrepareOptionsMenu(Menu menu) {
        // TODO Auto-generated method stub
        MenuItem item = menu.findItem(R.id.btn_action_search);
        item.setVisible(false);
        return super.onPrepareOptionsMenu(menu);
    }

    @Override
    protected void onNewIntent(Intent intent) {
        // TODO Auto-generated method stub
        super.onNewIntent(intent);
        query = getResources().getString(R.string.products_json_url)
                + "products?x=trick";
        handleIntent(intent);
    }

    @Override
    protected void onStart() {
        // TODO Auto-generated method stub
        super.onStart();
    }

    private void handleIntent(Intent intent) {
        if (Intent.ACTION_SEARCH.equals(intent.getAction())) {
            String title = intent.getStringExtra(SearchManager.QUERY);
            Log.i(TAG, title);
            try {
                title = URLEncoder.encode(title, "utf-8");
                Log.i(TAG, title);
                if (title != null && title != "") {
                    query += "&title=" + title;
                }
            } catch (UnsupportedEncodingException e) {
                // TODO Auto-generated catch block
                e.printStackTrace();
            }
        }
        btnAll = (Button) findViewById(R.id.btnAll);
        btnSell = (Button) findViewById(R.id.btnSell);
        btnBuy = (Button) findViewById(R.id.btnBuy);
        setButtonFocus(btnAll, R.drawable.tab_categories_pressed);

        rv = (RecyclerView) findViewById(R.id.rv);
        final LinearLayoutManager llm = new LinearLayoutManager(SearchActivity.this);
        rv.setLayoutManager(llm);

        Bundle bundle = getIntent().getExtras();
        if (bundle != null) {
            if (bundle.containsKey(constants.TITLE_KEY)) {
                String title = bundle.getString(constants.TITLE_KEY);
                if (title != null && title != "") {
                    try {
                        title = URLEncoder.encode(title, "utf-8");
                    } catch (UnsupportedEncodingException e) {
                        // TODO Auto-generated catch block
                        e.printStackTrace();
                    }
                    query += "&title=" + title;
                }
            }
        }

        btnAll.setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View v) {
                // TODO Auto-generated method stub
                setButtonFocus(btnAll, R.drawable.tab_categories_pressed);
                setButtonFocus(btnSell, R.drawable.tab_categories_normal);
                setButtonFocus(btnBuy, R.drawable.tab_categories_normal);
                first = 0;
                tmpQuery = query + "&first=" + first + "&offset="
                        + COUNT_ITEM_LOAD_MORE;
                loadingMore = false;
                products_list.clear();
                adapter.notifyDataSetChanged();
                new LoadMoreDataTask().execute();
            }
        });

        btnBuy.setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View v) {
                // TODO Auto-generated method stub
                setButtonFocus(btnBuy, R.drawable.tab_categories_pressed);
                setButtonFocus(btnAll, R.drawable.tab_categories_normal);
                setButtonFocus(btnSell, R.drawable.tab_categories_normal);
                first = 0;
                tmpQuery = query + "&first=" + first + "&offset="
                        + COUNT_ITEM_LOAD_MORE + "&aim=" + constants.BUY_VALUE;
                loadingMore = false;
                products_list.clear();
                adapter.notifyDataSetChanged();
                new LoadMoreDataTask().execute();
            }
        });

        btnSell.setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View v) {
                // TODO Auto-generated method stub
                setButtonFocus(btnSell, R.drawable.tab_categories_pressed);
                setButtonFocus(btnAll, R.drawable.tab_categories_normal);
                setButtonFocus(btnBuy, R.drawable.tab_categories_normal);
                first = 0;
                tmpQuery = query + "&first=" + first + "&offset="
                        + COUNT_ITEM_LOAD_MORE + "&aim=" + constants.SELL_VALUE;
                loadingMore = false;
                products_list.clear();
                adapter.notifyDataSetChanged();
                new LoadMoreDataTask().execute();
            }
        });

        loadMorePrg = (LinearLayout) findViewById(R.id.prgLoadMore);
        adapter = new ProductsAdapter(this,
                products_list);
        adapter.SetOnItemClickListener(new ProductsAdapter.OnItemClickListener() {
            @Override
            public void onItemClick(View view, int position) {
                Intent intent = new Intent(SearchActivity.this, DetailActivity.class);
                intent.putExtra(constants.COMMON_KEY, products_list.get(position)
                        .getId());
                startActivity(intent);
            }
        });
        rv.setAdapter(adapter);
        rv.setOnScrollListener(new RecyclerView.OnScrollListener() {
            @Override
            public void onScrolled(RecyclerView recyclerView, int dx, int dy) {
                super.onScrolled(recyclerView, dx, dy);
                visibleItemCount = llm.getChildCount();
                totalItemCount = llm.getItemCount();
                pastVisiblesItems = llm.findFirstVisibleItemPosition();
                if ((visibleItemCount + pastVisiblesItems) >= totalItemCount) {
                    loadingMore = false;
                    Log.v("...", "Last Item Wow !");
                    first += COUNT_ITEM_LOAD_MORE;
                    tmpQuery += "&first=" + first + "&offset=" + COUNT_ITEM_LOAD_MORE;
                    if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.HONEYCOMB) {
                        new LoadMoreDataTask()
                                .executeOnExecutor(AsyncTask.THREAD_POOL_EXECUTOR);
                    } else {
                        new LoadMoreDataTask().execute();
                    }
                }
            }
        });

        tmpQuery = query + "&first=" + first + "&offset="
                + COUNT_ITEM_LOAD_MORE;
        new LoadMoreDataTask().execute();


        swipeRefreshLayout = (SwipeRefreshLayout) findViewById(R.id.activity_main_swipe_refresh_layout);
        swipeRefreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                if (products_list != null && products_list.size() != 0) {
                    String pullQuery = query + "&first=" + 0 + "&offset="
                            + COUNT_ITEM_LOAD_MORE + "&sort_by=asc" + "&pull="
                            + products_list.get(0).getId();

                    if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.HONEYCOMB) {
                        new PullToRefreshDataTask(pullQuery)
                                .executeOnExecutor(AsyncTask.THREAD_POOL_EXECUTOR);
                    } else {
                        new PullToRefreshDataTask(pullQuery).execute();
                    }
                } else {
                    swipeRefreshLayout.setRefreshing(false);
                }
            }
        });
    }

    private void parse(JSONObject jsonObj, boolean append) {
        try {
            int id = jsonObj.getInt(Products.TAG_ID);
            String name = jsonObj.getString(Products.TAG_TITLE);
            String price = jsonObj.getString(Products.TAG_PRICE);
            String categories = jsonObj.getString(Products.TAG_CATEGORIES_NAME);
            String thumb = jsonObj.getString(Products.TAG_IMAGES);
            String datePost = jsonObj.getString(Products.TAG_DATE_POST);
            String fullName = jsonObj.getString(Products.TAG_FULL_NAME);
            String cities = jsonObj.getString(Products.TAG_CITIES);
            Products product = new Products();
            product.setId(id);
            product.setTitle(name);
            product.setPrice(price);
            product.setCategories(categories);
            product.setImages_path(thumb);
            product.setDate_post(datePost);
            product.setUser_name(fullName);
            product.setCities(cities);
            if (append) {
                products_list.add(product);
            } else {
                products_list.add(0, product);
            }
            Log.i("x", "xxxxxxxxxxx");
        } catch (Exception e) {
            // TODO: handle exception
            e.printStackTrace();
            loadingMore = true;
        }
    }

    private void parseAndAppend(String jsonString) {
        try {
            JSONArray jsonArray = new JSONArray(jsonString);
            for (int i = 0; i < jsonArray.length(); i++) {
                JSONObject jsonObj = jsonArray.getJSONObject(i);
                parse(jsonObj, true);
            }
            loadingMore = false;
            adapter.notifyDataSetChanged();
            loadMorePrg.setVisibility(View.GONE);
        } catch (Exception e) {
            // TODO: handle exception
            e.printStackTrace();
            loadingMore = true;
            loadMorePrg.setVisibility(View.GONE);
        }
    }

    private void parseAndPrepend(String jsonString) {
        try {
            JSONArray jsonArray = new JSONArray(jsonString);
            for (int i = 0; i < jsonArray.length(); i++) {
                JSONObject jsonObj = jsonArray.getJSONObject(i);
                parse(jsonObj, false);
            }
            loadingMore = false;
            adapter.notifyDataSetChanged();
            loadMorePrg.setVisibility(View.GONE);
        } catch (Exception e) {
            // TODO: handle exception
            loadingMore = false;
            e.printStackTrace();
            //mPullToRefreshLayout.setRefreshComplete();
            swipeRefreshLayout.setRefreshing(false);
        }
    }

    private String feedJson(String pullQuery) {
        try {
            HttpGet httpGet = null;
            DefaultHttpClient httpClient = new DefaultHttpClient();
            if (pullQuery != null && !pullQuery.equalsIgnoreCase("")) {
                httpGet = new HttpGet(pullQuery);
            } else {
                httpGet = new HttpGet(tmpQuery);
            }
            HttpResponse httpResponse = httpClient.execute(httpGet);
            HttpEntity httpEntity = httpResponse.getEntity();
            is = httpEntity.getContent();
        } catch (UnsupportedEncodingException e) {
            e.printStackTrace();
        } catch (ClientProtocolException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }

        try {
            BufferedReader reader = new BufferedReader(new InputStreamReader(
                    is, constants.STREAM_READER_CHARSET), 8);
            StringBuilder sb = new StringBuilder();
            String line = null;
            while ((line = reader.readLine()) != null) {
                sb.append(line + "\n");
            }
            is.close();
            jsonString = sb.toString();
            Log.i("JSON_FETCH_TAG", jsonString);
        } catch (Exception e) {
            Log.e("Buffer Error", "Error converting result " + e.toString());
        }
        ;
        return jsonString;
    }

    private class LoadMoreDataTask extends AsyncTask<Void, Void, String> {

        @Override
        protected String doInBackground(Void... params) {
            if (isCancelled()) {
                return null;
            }
            return feedJson(null);
        }

        @Override
        protected void onPostExecute(String result) {
            parseAndAppend(jsonString);
            super.onPostExecute(result);
        }

        @Override
        protected void onPreExecute() {
            // TODO Auto-generated method stub
            super.onPreExecute();
            loadMorePrg.setVisibility(View.VISIBLE);
            loadingMore = true;
        }

        @Override
        protected void onCancelled() {
            loadingMore = false;
            loadMorePrg.setVisibility(View.GONE);
        }
    }

    private OnItemClickListener listViewOnClick = new OnItemClickListener() {
        @Override
        public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
                                long arg3) {
            // TODO Auto-generated method stub
            Intent intent = new Intent(SearchActivity.this, DetailActivity.class);
            intent.putExtra(constants.COMMON_KEY, products_list.get(arg2)
                    .getId());
            startActivity(intent);
        }
    };


    private class PullToRefreshDataTask extends AsyncTask<Void, Void, String> {
        String pullQuery = null;

        public PullToRefreshDataTask(String pullQuery) {
            // TODO Auto-generated constructor stub
            this.pullQuery = pullQuery;
        }

        @Override
        protected String doInBackground(Void... params) {
            if (isCancelled()) {
                return null;
            }
            return feedJson(pullQuery);
        }

        @Override
        protected void onPostExecute(String result) {
            parseAndPrepend(result);
            swipeRefreshLayout.setRefreshing(false);
            //mPullToRefreshLayout.setRefreshComplete();
            super.onPostExecute(result);
        }

        @Override
        protected void onCancelled() {
            swipeRefreshLayout.setRefreshing(false);
            //mPullToRefreshLayout.setRefreshComplete();
        }
    }

}

